<?php

use LaravelBook\Ardent\Ardent;

class Ftp extends Ardent {

      protected $table                      = 'ftps';
      //fillables
      protected $fillable                   = ['username', 'hostname', 'homedir', 'password', 'password_confirmation'];
      //Rules of validations
      public static $rules                  = array(
          'username'              => 'required|unique:ftps,username',
          'hostname'              => 'required',
          'homedir'               => '',
          'password'              => 'required|min:8|confirmed',
          'password'              => array('regex:/^.*(?=.{8,15})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/'),
          'password_confirmation' => 'required',
      );
      //Relationships
      public static $relationsData          = array(
          'domain' => array(self::BELONGS_TO, 'Domain'),
      );
      //Auto Hydrate
      public $autoHydrateEntityFromInput    = true;
      public $forceEntityHydrationFromInput = true;
      public $autoPurgeRedundantAttributes  = true;

      public function beforeCreate()
      {
            if ($this->homedir == "")
            {
                  $this->homedir = "public_html/" . $this->domain->domain;
            }
            else
            {
                  $this->homedir = "public_html/" . $this->domain->domain . "/" . $this->homedir;
            }
            if (!count(Event::fire('ftp.creating', array($this))))
            {
                  return false;
            }
            array_forget($this, 'password');
      }

      public function beforeUpdate()
      {
            if ($this->password != "")
            {
                  if (!count(Event::fire('ftp.updating', array($this))))
                  {
                        return false;
                  }
            }
            array_forget($this, 'password');
      }

      public function beforeDelete()
      {
            if (!count(Event::fire('ftp.deleting', array($this))))
            {
                  return false;
            }
            array_forget($this, 'password');
      }

}
