<?php
/**
 * ContributorContact plugin
 *
 * @package   ContributorContact 
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * ContributorContact plugin class
 * 
 * @package ContributorContact 
 */
class ContributorContactPlugin extends Omeka_plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'define_acl',
        'admin_head',
        'before_save_record',
        'config_form',
        'config',
        'install',
        'uninstall'
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array('admin_navigation_main');

    /**
     * @var array Options and their default values.
     */
    protected $_options = array(
        'emailContribsPermalink' => 'false',
        'contribsPermalinkSubject' => 'Your contributed item is now publicly visible',
        'contribsPermalinkMessage' => 'Thank you for contributing! Your contributed item has been approved and can now be viewed at the following link: [link]'
    );

    /**
     * Install the plugin's options
     *
     * @return void
     */
    public function hookInstall()
    {
        $this->_installOptions();
    }

    /**
     * Uninstall the options
     *
     * @return void
     */
    public function hookUninstall()
    {
        $this->_uninstallOptions();
    }

    /**
     * Queue the javascript and css files to help the form work.
     *
     * This function runs before the admin section of the sit loads.
     * It queues the javascript and css files which help the form work,
     * so that they are loaded before any html output.
     *
     * @return void
     */
    public function hookAdminHead()
    {
        queue_js_file('ContributorContact');
       
    }

    /**
     * Define the plugin's access control list.
     *
     * @param array $args This array contains a reference to
     * the zend ACL under it's 'acl' key.
     * @return void
     */
    public function hookDefineAcl($args)
    {
        $args['acl']->addResource('ContributorContact_Index');
    }

    /**
     * Add the ContributorContact link to the admin main navigation.
     * 
     * @param array $nav Array of links for admin nav section
     * @return array $nav Updated array of links for admin nav section
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Contact Contributors'),
            'uri' => url('contributor-contact'),
            'resource' => 'ContributorContact_Index',
            'privilege' => 'index'
        );
        return $nav;
    }

    
    /**
     * When an item is saved, determine whether it is a
     * contributed item being made public. If so, email
     * the owner.
     * 
     *@param array $args An array of parameters passed by the hook
     * @return void
     */
    public function hookBeforeSaveRecord($args)
    {
      $item = $args['record'];
      $oldItem = get_record_by_id('Item',$item->id);

      if(!$oldItem)
          return;

      $contributedItem = $this->_db->getTable("ContributionContributedItem")->findByItem($oldItem);

      if (get_class($contributedItem) != "ContributionContributedItem")
          return;
      $contributor = $contributedItem->getContributor();
      if(!$contributor)
          return;

      $oldItem = get_record_by_id('Item',$item->id);
      if($item->public && !$oldItem->public && get_option('emailContribsPermalink') == true) {
          $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $subject = get_option('contribsPermalinkSubject');
          $message = get_option('contribsPermalinkMessage');
          $message = str_replace('[link]',link_to($item),$message);
          $from = get_option('administrator_email');
          $mail = new Zend_Mail('UTF-8');
          $mail->setBodyHtml($message);
          $mail->setFrom($from, "$siteTitle Administrator");
          $mail->addTo($contributor->email, $contributor->name);
          $mail->setSubject($subject);
          $mail->addHeader('X-Mailer', 'PHP/' . phpversion());
          $mail->send();
      }
    }
    /**
     * Display the plugin config form.
     */
    public function hookConfigForm()
    {
        require dirname(__FILE__) . '/forms/config_form.php';
    }

    /**
     * Set the options from the config form input.
     */
    public function hookConfig()
    {
        set_option('contribsPermalinkSubject', $_POST['contribsPermalinkSubject']);
        set_option('contribsPermalinkMessage', $_POST['contribsPermalinkMessage']);
        set_option('emailContribsPermalink', $_POST['emailContribsPermalink']);
    }

}
