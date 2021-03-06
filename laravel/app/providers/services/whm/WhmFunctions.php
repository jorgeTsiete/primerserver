<?php

namespace PrimerServer\Services\WHM;

/**
 * Description of UserRepositoryEloquent
 *
 * @author carlos
 */
use Administrador;

class WHMFunctions {

      //Constructs the objects and uses IoC automatically to call xmlApi
      public function __construct(\XmlApi $xmlapi)
      {
            $this->xmlapi = $xmlapi;
            $this->xmlapi->set_host(\Config::get('whm.host'));
            $this->xmlapi->set_user(\Config::get('whm.username'));
            $this->xmlapi->set_hash(\Config::get('whm.hash'));
            $this->xmlapi->set_output('json');
            //$this->plan   = $plan;
      }

      /*
       * List all of the addon domains that are associated with your server.
       * regex (Regular Expression (optional)) 
       */

      public function listSubDomain($nameserver, $subdomain)
      {
            if (!isset($nameserver) || !isset($subdomain))
            {
                  \Session::flash('error', trans('frontend.messages.domain.store.no_data'));
                  return false;
            }
            $response = $this->xmlapi->api2_query($nameserver, "AddonDomain", "listaddondomains", array('regex' => $subdomain));

            $resultado = json_decode($response, true);
            if (count($resultado['cpanelresult']['data']) > 0)
            {
                  return true;
            }
            else
            {
                  return false;
            }
      }

      /*
       * Add subdomains to server
       * requires: 
       * $nameserver: realname of the hosted server account
       * $domain: The domain name of the addon domain you wish to create. (For example, domain.sub.example.com).
       * $subdomain: The domain name of the addon domain you wish to create. (For example, sub.example.com).
       * $password: password used for the ftp
       * 
       */

      public function addSubDomain($nameserver, $domain = null, $subdomain = null, $password = null)
      {
            if (!isset($nameserver) || !isset($domain) || !isset($subdomain) || !isset($password))
            {
                  \Session::flash('error', trans('frontend.messages.domain.store.no_data'));
                  return false;
            }
            $response = $this->xmlapi->api2_query($nameserver, "AddonDomain", "addaddondomain", array('newdomain' => $domain, 'dir' => "public_html/" . $domain, 'subdomain' => $subdomain, 'pass' => $password));

            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. addSubDomain ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * Add subdomains to server
       * requires: 
       * $nameserver: real name of the hosted server account
       * $domain: The addon domain you wish to delete.
       * $subdomain: This value should contain the addon domain's username followed by an underscore (_), then the addon domain's main domain 
       */

      public function delSubDomain($nameserver, $domain, $subdomain)
      {

            if (!isset($nameserver) || !isset($domain) || !isset($subdomain))
            {
                  \Session::flash('error', trans('frontend.messages.domain.store.no_data'));
                  return false;
            }

            $response  = $this->xmlapi->api2_query($nameserver, 'AddonDomain', 'deladdondomain', array('domain' => $domain, 'subdomain' => $subdomain));
            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. delSubDomain ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * FTP
       */

      /*
       * Add an FTP Account to the server
       * user (string)	The username portion of the new FTP account, without the domain.When you log in with this name, remember to append the main domain to the end (for example, user@example.com).
       * pass (string)	The password for the new FTP account.
       * quota (integer)	The new FTP account's quota. 0 indicates that the account will not use a quota. This parameter defaults to 0.
       * homedir (string)	The path to the FTP account's root directory. This value should be relative to the account's home directory. */

      public function addFTP($nameserver, $user, $pass, $quota, $homedir)
      {

            if (!isset($nameserver) || !isset($user) || !isset($pass) || !isset($quota) || !isset($homedir))
            {
                  \Session::flash('error', trans('frontend.messages.ftp.store.no_data'));
                  return false;
            }

            $response  = $this->xmlapi->api2_query($nameserver, "Ftp", "addftp", array('pass' => $pass, 'user' => $user, 'quota' => $quota, 'homedir' => $homedir));
            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. addFTP ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * Change an FTP account's password. This function is only available in cPanel 11.27.x and later.
       * user (string)	The name of the FTP account whose password should be changed.
       * pass (string)	The new password for the FTP account.
       */

      public function updateFTP($nameserver, $user, $pass)
      {
            if (!isset($nameserver) || !isset($user) || !isset($pass))
            {
                  \Session::flash('error', trans('frontend.messages.ftp.update.no_data'));
                  return false;
            }

            $response = $this->xmlapi->api2_query($nameserver, 'Ftp', 'passwd', array('user' => $user, 'pass' => $pass));

            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. updateFTP ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * Delete an FTP account. This function is only available in cPanel 11.27.x and later.
       * user (string)	The name of the FTP account to be removed.
       * destroy (boolean)    A boolean value that indicates whether or not the FTP account's home directory should also be deleted. A value of 1 indicates that the directory should be removed. This value defaults to 0.      Returns:
       */

      public function delFTP($nameserver, $user, $destroy)
      {
            if (!isset($nameserver) || !isset($user) || !isset($destroy))
            {
                  \Session::flash('error', trans('frontend.messages.ftp.destroy.no_data'));
                  return false;
            }

            $response = $this->xmlapi->api2_query($nameserver, 'Ftp', 'delftp', array('user' => $user, 'destroy' => $destroy));

            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. delFTP ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * Mails
       */

      /*
       * This function adds a new email account.
       * domain (string)	The domain for the new email account. (For example, example.com if the address is user@example.com.)
       * email (string)	The username for the new email account. (For example, user if the address is user@example.com.)
       * password (string)	The password for the new email account.
       * quota (integer)	A positive integer that defines the disk quota for the email account. A value of 0 indicates an unlimited quota.
       */

      public function addMail($nameserver, $domain, $email, $password, $quota)
      {
            if (!isset($nameserver) || !isset($domain) || !isset($email) || !isset($password) || !isset($quota))
            {
                  \Session::flash('error', trans('frontend.messages.mail.store.no_data'));
                  return false;
            }

            $response = $this->xmlapi->api2_query($nameserver, 'Email', 'addpop', array('domain' => $domain, 'email' => $email, 'password' => $password, 'quota' => $quota));

            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. addMail ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * This function deletes an email account.
       * domain (string)	The domain for the email account you wish to remove. (For example, example.com if the address is user@example.com.)
       * email (string)	The username for the email address you wish to remove. (For example, user if the address is user@example.com.)
       */

      public function delMail($nameserver, $domain, $email)
      {

            if (!isset($nameserver) || !isset($domain) || !isset($email))
            {
                  \Session::flash('error', trans('frontend.messages.mail.destroy.no_data'));
                  return false;
            }

            $response = $this->xmlapi->api2_query($nameserver, 'Email', 'delpop', array('domain' => $domain, 'email' => $email,));

            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. delMail ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * This function changes an email account's password.
       * domain (string)	The domain for the email address for which you wish to change the password. (For example, example.com if the address is user@example.com.)
       * email (string)	The username for the email address for which you wish to change the password. (For example, user if the address is user@example.com.)
       * password (string) The desired password for the account.
       */

      public function changePassword($nameserver, $domain, $email, $password)
      {
            if (!isset($nameserver) || !isset($domain) || !isset($email) || !isset($password))
            {
                  \Session::flash('error', trans('frontend.messages.mail.edit.no_data'));
                  return false;
            }

            $response = $this->xmlapi->api2_query($nameserver, 'Email', 'passwdpop', array('domain' => $domain, 'email' => $email, 'password' => $password));


            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. changePassword ' . $resultado['cpanelresult']['data'][0]['reason']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $resultado['cpanelresult']['data'][0]['reason'])));
                  return false;
            }
      }

      /*
       * Creates an email forwarder for the specified address. You can forward mail to a new address or pipe mail to a program.
       * domain (string)	The domain for which you wish to add a forwarder. (For example, example.com.)
       * email (string )	The username of the email address for which you wish to add a forwarder. (For example, user if the address is user@example.com.)
       * fwdopt (string)	This parameter defines which type of forwarder you wish to use. The valid values for this option are:
       * fwd — Forwards to another non-system email address.     
       */

      public function addForward($nameserver, $domain, $email, $forward)
      {
            if (!isset($nameserver) || !isset($domain) || !isset($email) || !isset($forward))
            {
                  \Session::flash('error', trans('frontend.messages.mail.edit.no_data'));
                  return false;
            }

            $response = $this->xmlapi->api2_query($nameserver, 'Email', 'addforward', array('domain' => $domain, 'email' => $email, 'fwdopt' => 'fwd', 'fwdemail' => $forward));

            $resultado = json_decode($response, true);
            if (count($resultado['cpanelresult']['data']))
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. addForward error');
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array("error" => "error")));
                  return false;
            }
      }

      /*
       * Delete a mail forwarder.
       * forwarder (string)	The forwarder to delete. This parameter should contain both the local email address and the destination address, separated by an equal (=) sign. For example, a forwarder from user@example.com to user@example.net should be formatted as user@example.com=user@example.net.
       */

      public function delForward($nameserver, $email, $forward)
      {
            if (!isset($nameserver) || !isset($email) || !isset($forward))
            {
                  \Session::flash('error', trans('frontend.messages.mail.edit.no_data'));
                  return false;
            }

            try {
                  $this->xmlapi->api1_query($nameserver, 'Email', 'delforward', array($email . '=' . $forward));
                  return true;
            } catch (Exception $e) {
                  \Log::error('WHMFunciones. delForward ' . $e->getMessage());
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array('error' => $e->getMessage())));
                  return false;
            }
      }

      /*
       * Databases
       */

      /*
       * Create a database.
       * db	string	The name of the database that you wish to create.
       * 
       * Create a database user, and set a password for that user
       * dbuser	string	The username for the new database user that you wish to create.
       * password	string	The password for the new database user. 
       * 
       * Grant privileges to a specified user on a database.
       * privileges	string	The privileges that you wish to grant to the database user on the specified database.
       * db	string	The database to which you wish to grant privileges to the specified user.
       * dbuser	string	The username to which you wish to grant privileges to on the specified database.
       */

      public function addDb($nameserver, $dbuser, $password, $db)
      {

            if (!isset($nameserver) || !isset($dbuser) || !isset($password) || !isset($db))
            {
                  \Session::flash('error', trans('frontend.messages.dbase.store.no_data'));
                  return false;
            }

            $response  = $this->xmlapi->api2_query($nameserver, "MysqlFE", "createdb", array("db" => $db));
            $resultado = json_decode($response, true);
            if (!isset($resultado['cpanelresult']['error']))
            {
                  $response  = $this->xmlapi->api2_query($nameserver, "MysqlFE", "createdbuser", array("dbuser" => $dbuser, "password" => $password));
                  $resultado = json_decode($response, true);
                  if (!isset($resultado['cpanelresult']['error']))
                  {

                        $response  = $this->xmlapi->api2_query($nameserver, "MysqlFE", "setdbuserprivileges", array("privileges" => "All", "db" => $db, "dbuser" => $dbuser));
                        $resultado = json_decode($response, true);
                        if (!isset($resultado['cpanelresult']['error']))
                        {
                              return true;
                        }
                        else
                        {
                              \Log::error('WHMFunciones. addDb ' . $resultado['cpanelresult']['error']);
                              \Session::flash("error", trans('frontend.messages.domain.store.server_error', array("error" => $resultado['cpanelresult']['error'])));
                              return false;
                        }
                  }
                  else
                  {
                        \Log::error('WHMFunciones. addDb ' . $resultado['cpanelresult']['error']);
                        \Session::flash("error", trans('frontend.messages.domain.store.server_error', array("error" => $resultado['cpanelresult']['error'])));
                        return false;
                  }
            }
            else
            {
                  \Log::error('WHMFunciones. addDb ' . $resultado['cpanelresult']['error']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array("error" => $resultado['cpanelresult']['error'])));
                  return false;
            }
      }

      public function delDb($nameserver, $db, $dbuser)
      {
            if (!isset($nameserver) || !isset($dbuser) || !isset($db))
            {
                  \Session::flash('error', trans('frontend.messages.dbase.store.no_data'));
                  return false;
            }

            $response  = $this->xmlapi->api2_query($nameserver, "MysqlFE", "deletedbuser", array("dbuser" => $dbuser));
            $resultado = json_decode($response, true);
            if (!isset($resultado['cpanelresult']['error']))
            {
                  $response  = $this->xmlapi->api2_query($nameserver, "MysqlFE", "deletedb", array("db" => $db));
                  $resultado = json_decode($response, true);
                  if (!isset($resultado['cpanelresult']['error']))
                  {
                        return true;
                  }
                  else
                  {
                        \Log::error('WHMFunciones. addDb ' . $resultado['cpanelresult']['error']);
                        \Session::flash("error", trans('frontend.messages.domain.store.server_error', array("error" => $resultado['cpanelresult']['error'])));
                        return false;
                  }
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. addDb ' . $resultado['cpanelresult']['error']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array("error" => $resultado['cpanelresult']['error'])));
                  return false;
            }
      }

      public function updateDb($nameserver,$dbuser,$password)
      {
            if (!isset($nameserver) || !isset($dbuser) || !isset($password))
            {
                  \Session::flash('error', trans('frontend.messages.dbase.store.no_data'));
                  return false;
            }

            $response  = $this->xmlapi->api2_query($nameserver, "MysqlFE", "changedbuserpassword", array("dbuser" => $dbuser,'password'=>$password));
            $resultado = json_decode($response, true);
            if (!isset($resultado['cpanelresult']['error']))
            {
                  return true;
            }
            else
            {
                  \Log::error('WHMFunciones. addDb ' . $resultado['cpanelresult']['error']);
                  \Session::flash("error", trans('frontend.messages.domain.store.server_error', array("error" => $resultado['cpanelresult']['error'])));
                  return false;
            }
      }

}
