<?php

use Illuminate\Events\Dispatcher;

class EnomPaymentListener {

      public function __construct(Dispatcher $events)
      {
            $this->events = $events;
      }

      /*
       * Una vez que el pago ya esta realizado, Compramos el dominio en enom,
       */

      public function store($payments)
      {
            //Aqui seleccionaremos en el futuro, las actualizaciones de objetos que se realizarán según el tipo de pago
            $payment = $payments[0];
            $domain  = $payment->domain;


            $sld = substr($domain->domain, 0, strpos($domain->domain, '.'));
            $tld = substr($domain->domain, strpos($domain->domain, '.') + 1);

            $enom = new PrimerServer\Services\Enom\Enom();

            if ($enom->check_domain($sld, $tld))
            {
            
                  $enom = new PrimerServer\Services\Enom\Enom();
                  if ($enom->buy_domain($sld, $tld))
                  {
                        return true;
                  }
            }

            //Envia correo electrónico para modificar el dominio y pagarlo directamente
            $this->events->fire("enom.domain.buy.error", array($domain));
            return false;
      }

}
