<?php

class Application_Form_FormMessageFormatter
{
   /**
     * return error messages appropriate for javascript at client side
     */
    public function getMessagesForAjax($form)
    {
        $messages=null;
        $errors= $form->getMessages();
            foreach($errors as $key=>$row)
            {
                if (!empty($row) && $key != 'submit') {
                    foreach($row as $keyer => $rower)
                    {
                        $messages[$key][] = $rower;    
                    }
                }
            }
            
        return $messages;    
    }
}