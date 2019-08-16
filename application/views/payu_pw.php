<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head><title>
	Untitled Page
</title></head>
<body>
    <!-- https://test.payu.in/_payment -->
    <form id="form1" action="https://test.payu.in/_payment"  method="post">
        <input type="hidden" name="hash" value="<?php echo $pg['hash']?>" />
        <input type="hidden" name="txnid" value="<?php echo $pg['txnid']?>" />
        <input type="hidden" name="key" value="<?php echo $pg['key']?>" />
        <input type="hidden" name="amount" value="<?php echo $pg['final_amount']?>" />
        <input type="hidden" name="firstname" value="<?php echo $pg['firstname']?>" />
        <input type="hidden" name="email" value="<?php echo $pg['email']?>" />
        <input type="hidden" name="phone" value="<?php echo $pg['phone']?>" />
        <input type="hidden" name="productinfo" value="<?php echo $pg['productinfo']?>" />
        <input type="hidden" name="surl" value="<?php echo $pg['surl']?>" />
        <input type="hidden" name="furl" value="<?php echo $pg['furl']?>" />
        <input type="hidden" name="lastname" value="<?php echo $pg['lastname']?>" />
        <input type="hidden" name="curl" value="<?php echo $pg['curl']?>" />
        <input type="hidden" name="address1" value="<?php echo $pg['address1']?>" />
        <input type="hidden" name="address2" value="<?php echo $pg['address2']?>" />
        <input type="hidden" name="city" value="<?php echo $pg['city']?>" />
        <input type="hidden" name="state" value="<?php echo $pg['state']?>" />
        <input type="hidden" name="country" value="<?php echo $pg['country']?>" />
        <input type="hidden" name="zipcode" value="<?php echo $pg['zipcode']?>" />
        <input type="hidden" name="udf1" value="<?php echo $pg['payment_mode']?>" />
        <input type="hidden" name="udf2" value="" />
        <input type="hidden" name="udf3" value="" />
        <input type="hidden" name="udf4" value="" />
        <input type="hidden" name="udf5" value="" />
        <input type="hidden" name="pg" value="<?php echo $pg['payment_mode']?>" />
        <input type="hidden" name="bankcode" value="<?php echo $pg['bankcode']?>"/>
        <input type="hidden" name="enforce_paymethod" value="<?php echo $pg['enforce_paymethod']?>" />
    </form>
    <div id="showerr" style="display:none;">
    </div> 
</body>

<script type ="text/javascript">   
	 document.getElementById('form1').submit(); 
	</script>
	
</html>
