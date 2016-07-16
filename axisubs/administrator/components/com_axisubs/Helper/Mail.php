<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

defined( '_JEXEC' ) or die( 'Restricted access' );

use Flycart\Axisubs\Admin\Model\Mixin\CarbonHelper;
use Carbon\Carbon;
use JFactory;
use JText;

/**
 * Mail helper
 */
class Mail{

	public static $instance = null;	

	/**
	 * get an instance
	 * @param array $config
	 * @return \Flycart\Axisubs\Admin\Helper\Permission
	 * * */
	public static function getInstance(array $config = array())
	{
		if (!self::$instance)
		{
			self::$instance = new self($config);
		}
		return self::$instance;
	}

	/**
	 * Method to get the mailer object
	 * */
	function getMailer(){
		// get the type of mailer configured from component settings
		$mailer_name = 'Joomla' ;

		if ( $mailer_name == 'Joomla' ){
			$mailer = clone JFactory::getMailer();
			$isHTML = true;
			$mailer->IsHTML($isHTML);
			// Required in order not to get broken characters
			$mailer->CharSet = 'UTF-8';
		}
		//implement more mailers like swiftmailer in future

		return $mailer;
	}

	/**
	 * Sends error messages to site administrators
	 *
	 * @param string $message
	 * @param string $paymentData
	 * @return boolean
	 * @access protected
	 */
	public function sendErrorEmails($receiver, $subject, $body,  $cc = null, $bcc = null)
	{
		if(!isset($receiver)) return false;

		$mainframe = JFactory::getApplication();
		$config = JFactory::getConfig();

		$mailer = $this->getMailer();
		$mailfrom = $config->get('mailfrom');
		$fromname = $config->get('fromname');
		$mailer->setSender(array( $mailfrom, $fromname ));

		$mailer->addRecipient($receiver);
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		$mailer->addCC($cc);
		$mailer->addCC($bcc);

		return $mailer->Send();
	}

	/**
	 * Method to send the emails for the supplied event and objects
	 * */
	function sendEmails( $event_name ='', $objects ){
		// first get the list of email templates
		$config = JFactory::getConfig();

		$mail_templates = array();
		$mail_templates = $this->getMailTemplates( $event_name , $objects );
		if ( count($mail_templates) > 0 ) {
			foreach ($mail_templates as $template) {
				// prepare the mail template
				$template = $this->processTemplate( $template , $objects );
				
				// 1 - get the mailer
				$mailer = $this->getMailer();

				// 2 - intialize the mailer with proper sender information
				if(version_compare(JVERSION, '3.0', 'ge')) {
					$mailfrom = $config->get('mailfrom');
					$fromname = $config->get('fromname');
				} else {
					$mailfrom = $config->getValue('config.mailfrom');
					$fromname = $config->getValue('config.fromname');
				}
				$mailer->setSender(array( $mailfrom, $fromname ));

				// 3- set encoding and other information
				$mailer->CharSet = 'UTF-8';

				// 4 - set subject, body 
				$mailer->setSubject( $template->subject );

				$lang = JFactory::getLanguage();
				$htmlExtra = '';
				if($lang->isRTL()) {
					$htmlExtra = ' dir="rtl"';
				}
				$body = '<html'.$htmlExtra.'><head>'.
						'<meta http-equiv="Content-Type" content="text/html; charset='.$mailer->CharSet.'">
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
						</head>'.'<body>'.$template->body_html.'</body></html>';
				$mailer->setBody($body);
				$mailer->AltBody = $this->textVersion( $template->body_plain );

				// 5 - set the recipients
				$send_flag = false;
				if ( count($template->recipients) > 0 ) {
					$mailer->addRecipient( $template->recipients ); 	
					$send_flag = true;
				}

				if ( count($template->cc) > 0 ) {
					$mailer->addCC( $template->cc ); 	
					$send_flag = true;
				}

				if ( count($template->bcc) > 0 ) {
					$mailer->addBCC( $template->bcc ); 	
					$send_flag = true;
				}
				
				// 6 - send the mail
				if ( $send_flag ) {
					$mailer->Send();	
				}				

			}
		}
	}

	/**
	 * Get the email template for the event passed
	 * @param string $event_name event name or the email type for the mail templates needs to be loaded
	 * */
	function getMailTemplates( $event_name ='', $objects ){
		if (empty($event_name)){
			return array();
		}

		// get subscribed user's language or preferred language
		$user_lang = $this->getLanguage( $objects );

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
		->select('*')
		->from('#__axisubs_emailtemplates')
		->where($db->qn('enabled').'='.$db->q(1))
		->where($db->qn('event').'='.$db->q($event_name));
		$db->setQuery($query);

		try {
			$mail_templates = $db->loadObjectList();

			$etcontent_model = $this->getModel('EmailTemplateContents');

			foreach ($mail_templates as $template) {
				$etcontent = $etcontent_model ->emailtemplate_id($template->axisubs_emailtemplate_id)
								->language_id( $user_lang )
								->get()
								->toArray();
				foreach ($etcontent as $c) {
					$template->$c['field'] = $c['content'];
				}
			}

			// unset if the subject and body html fields are not present
			foreach ($mail_templates as $k => $template) {
				// check if subject and body_html / body_plain should not be empty
				if ( !isset($template->subject) ) {
					unset( $mail_templates[$k] ); continue;
				}

				if ( !isset($template->body_html) || !isset($template->body_plain) ) {
					unset( $mail_templates[$k] ); continue;
				}

				if ( empty($template->subject) ) {
					unset( $mail_templates[$k] ); continue;
				}

				if ( empty($template->body_html) && empty($template->body_plain) ) {
					unset( $mail_templates[$k] ); continue;
				}

			}

		} catch (\Exception $e) {
			$mail_templates = array();
		}

		return $mail_templates;
	}

	/**
	 * get the language id
	 * */
	function getLanguage( $objects ){
		$lang_code = JFactory::getLanguage()->getTag(); // site's default lang code

		$config = \JFactory::getConfig();
		$lang_code = $config->get('language');
		
		if (isset($objects['subscription']) && isset( $objects['subscription']->language ) 
			&& !empty( $objects['subscription']->language ) ) {
			$lang_code = $objects['subscription']->language;
		}
		// now find the language id for the language code
		$lang_id = 0;

		$languages = \JLanguageHelper::getLanguages();
		foreach ($languages as $lang) {
			if ($lang->lang_code == $lang_code ) {
				$lang_id = $lang->lang_id;
			}
		}

		return $lang_id;
	}

	/**
	 * Process the mail template
	 * involves preparing the email body by processing the short tags in the mail content
	 * */
	function processTemplate( $template, $objects ){

		$shortcodes = Axisubs::shortcodes();
		
		if (isset( $objects['subscription'] ) ) {
			$shortcodes->bindSubscription( $objects['subscription'] );
		}elseif (isset($objects['customer']) ) {
			$shortcodes->bindSubscription( $objects['customer'] );
		}elseif (isset($objects['plan']) ) {
			$shortcodes->bindSubscription( $objects['plan'] );
		}		

		$template->subject 		= $shortcodes->processContent( $template->subject , $objects );
		$template->body_html 	= $shortcodes->processContent( $template->body_html , $objects );
		$template->body_plain 	= $shortcodes->processContent( $template->body_plain , $objects );

		$template->recipients = $shortcodes->processRecipients( $template->recipients, $objects );
		$template->cc = $shortcodes->processRecipients( $template->cc, $objects );
		$template->bcc = $shortcodes->processRecipients( $template->bcc, $objects );

		return $template;
	}

	public function getModel($model_name){
		$container = \FOF30\Container\Container::getInstance('com_axisubs');
		$model = $container->factory->model($model_name);	
		return $model;
	}

	/**
	 * Method to extract a plain text email from a html email
	 * strip html tags and other unwanted html stuff
	 * @param 	string $html html content of the mail body
	 * @return 	string plain text content of the mail body
	 * */
	function textVersion($html){

		$html = preg_replace('# +#',' ',$html);
		$html = str_replace(array("\n","\r","\t"),'',$html);
		$removeScript = "#< *script(?:(?!< */ *script *>).)*< */ *script *>#isU";
		$removeStyle = "#< *style(?:(?!< */ *style *>).)*< */ *style *>#isU";
		$removeStrikeTags =  '#< *strike(?:(?!< */ *strike *>).)*< */ *strike *>#iU';
		$replaceByTwoReturnChar = '#< *(h1|h2)[^>]*>#Ui';
		$replaceByStars = '#< *li[^>]*>#Ui';
		$replaceByReturnChar1 = '#< */ *(li|td|tr|div|p)[^>]*> *< *(li|td|tr|div|p)[^>]*>#Ui';
		$replaceByReturnChar = '#< */? *(br|p|h1|h2|h3|li|ul|h4|h5|h6|tr|td|div)[^>]*>#Ui';
		$replaceLinks = '/< *a[^>]*href *= *"([^"]*)"[^>]*>(.*)< *\/ *a *>/Uis';
		$text = preg_replace(array($removeScript,$removeStyle,$removeStrikeTags,$replaceByTwoReturnChar,$replaceByStars,$replaceByReturnChar1,$replaceByReturnChar,$replaceLinks),array('','','',"\n\n","\n* ","\n","\n",'${2} ( ${1} )'),$html);
		$text = str_replace(array(" ","&nbsp;"),' ',strip_tags($text));
		$text = trim(@html_entity_decode($text,ENT_QUOTES,'UTF-8'));
		$text = preg_replace('# +#',' ',$text);
		$text = preg_replace('#\n *\n\s+#',"\n\n",$text);
		return $text;
	}

}