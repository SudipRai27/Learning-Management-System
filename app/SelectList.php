<?php

namespace App;

class SelectList {

	public function generateDynamicSelectList($data, $selectedId, $optionValue, $displayName)
	{        
        $select = '';
        $select .= '<option value=""> Select  </option>';
        foreach($data as $index => $d)
        {
            if($d->$optionValue == $selectedId)
            {
                $select .= '<option value='.$d->$optionValue.' selected>'.$d->$displayName.'</option>';
            }
            else
            {
                $select .= '<option value='.$d->$optionValue.'>'.$d->$displayName.'</option>';   
            }
            
        }
        $select.= '</select>';
        return $select;        
	}

	public function generateDynamicSelectListWithOneAdditionalParam($data, $selectedId, $optionValue, $displayName, $additionalParam)
	{
		$select = '';
        $select .= '<option value=""> Select  </option>';
		foreach($data as $index => $d)
        {
            if($d->$optionValue == $selectedId)
            {
                $select .= '<option value='.$d->$optionValue.' selected>'.$d->$displayName.' - '. $d->$additionalParam.'</option>';
            }
            else
            {
                $select .= '<option value='.$d->$optionValue.'>'.$d->$displayName. ' - ' . $d->$additionalParam.'</option>';   
            }
            
        }
        $select.= '</select>';
        return $select; 

	}

    public function generateDynamicSelectListWithMultipleParams($data, $selectedId, $optionValue, $displayName, $params=[]) {


        $select = '';
        $select .= '<option value=""> Select  </option>';
        foreach($data as $index => $d)
        {
            if($d[$optionValue] == $selectedId)
            {
                $select .= '<option value='.$d[$optionValue].' selected>'.$d[$displayName].' - '. (isset($params[0]) ? $d[$params[0]] : ''). ' -- ' . (isset($params[1]) ? $d[$params[1]] : '').'</option>';
            }
            else
            {

                $select .= '<option value='.$d[$optionValue].'>'.$d[$displayName].' - '. (isset($params[0]) ? $d[$params[0]] : ''). ' -- '. (isset($params[1]) ? $d[$params[1]] : '').'</option>';
            }
            
        }
        $select.= '</select>';
        return $select; 

    }
}