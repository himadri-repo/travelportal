<?php

class TransactionResponse {

    private $respHashKey = "";
    private $is_encrypted_response = false;

    /**
     * @return string
     */
    public function getRespHashKey()
    {
        return $this->respHashKey;
    }

    /**
     * @param string $respHashKey
     */
    public function setRespHashKey($respHashKey)
    {
        $this->respHashKey = $respHashKey;
    }

    public function getIsEncryptedResponse() {
        return $this->is_encrypted_response;
    }

    public function validateResponse($responseParams)
    {
        $is_encrypted_response = isset($responseParams["encdata"]);
        $status = strtolower($responseParams['f_code']);

        if(!$is_encrypted_response) {
            $str = $responseParams["mmp_txn"].$responseParams["mer_txn"].$responseParams["f_code"].$responseParams["prod"].$responseParams["discriminator"].$responseParams["amt"].$responseParams["bank_txn"];
            $signature =  hash_hmac("sha512",$str,$this->respHashKey,false);
            if($signature == $responseParams["signature"] && $status === 'ok'){
                return true;
            } else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}
