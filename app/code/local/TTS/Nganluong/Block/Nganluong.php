<?php
class TTS_Nganluong_Block_Nganluong extends Mage_Checkout_Block_Onepage_Success
{
	private $nganluong_url ;
	private $merchant_site_code;	
	private $secure_pass; 
	
	function __construct($nganluong_url,$merchant_site_code,$secure_pass)
			{
				 $this->nganluong_url=$nganluong_url;
				 $this->merchant_site_code=$merchant_site_code;
				 $this->secure_pass=$secure_pass;
			}
	public function buildCheckoutUrlNew($return_url, $receiver, $transaction_info, $order_code, $price, $currency = 'vnd', $quantity = 0, $tax = 0, $discount = 0, $fee_cal = 0, $fee_shipping = 0, $order_description = '', $buyer_info = '', $affiliate_code = '')
	{	
		$arr_param = array(
			'merchant_site_code'=>	strval($this->merchant_site_code),
			'return_url'		=>	strval(strtolower($return_url)),
			'receiver'			=>	strval($receiver),
			'transaction_info'	=>	strval($transaction_info),
			'order_code'		=>	strval($order_code),
			'price'				=>	strval($price),
			'currency'			=>	strval($currency),
			'quantity'			=>	strval($quantity),
			'tax'				=>	strval($tax),
			'discount'			=>	strval($discount),
			'fee_cal'			=>	strval($fee_cal),
			'fee_shipping'		=>	strval($fee_shipping),
			'order_description'	=>	strval($order_description),
			'buyer_info'		=>	strval($buyer_info),
			'affiliate_code'	=>	strval($affiliate_code)
		);
		$secure_code ='';
		$secure_code = implode(' ', $arr_param) . ' ' . $this->secure_pass;
		$arr_param['secure_code'] = md5($secure_code);
		/* */
		$redirect_url = $this->nganluong_url;
		if (strpos($redirect_url, '?') === false) {
			$redirect_url .= '?';
		} else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false) {
			$redirect_url .= '&';			
		}
				
		/* */
		$url = '';
		foreach ($arr_param as $key=>$value) {
			$value = urlencode($value);
			if ($url == '') {
				$url .= $key . '=' . $value;
			} else {
				$url .= '&' . $key . '=' . $value;
			}
		}
		
		return $redirect_url.$url;
	}
	
	//Hàm xây d?ng url, trong dó có tham s? mã hóa (còn g?i là public key)
	public function buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price)
	{
		
		// M?ng các tham s? chuy?n t?i nganluong.vn
		$arr_param = array(
			'merchant_site_code'=>	strval($this->merchant_site_code),
			'return_url'		=>	strtolower(urlencode($return_url)),
			'receiver'			=>	strval($receiver),
			'transaction_info'	=>	strval($transaction_info),
			'order_code'		=>	strval($order_code),
			'price'				=>	strval($price)					
		);
		$secure_code ='';
		$secure_code = implode(' ', $arr_param) . ' ' . $this->secure_pass;
		$arr_param['secure_code'] = md5($secure_code);
		
		/* Bu?c 2. Ki?m tra  bi?n $redirect_url xem có '?' không, n?u không có thì b? sung vào*/
		$redirect_url = $this->nganluong_url;
		if (strpos($redirect_url, '?') === false)
		{
			$redirect_url .= '?';
		}
		else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
		{
			$redirect_url .= '&';			
		}
				
		/* Bu?c 3. t?o url*/
		$url = '';
		foreach ($arr_param as $key=>$value)
		{
			if ($key != 'return_url') $value = urlencode($value);
			
			if ($url == '')
				$url .= $key . '=' . $value;
			else
				$url .= '&' . $key . '=' . $value;
		}
		
		return $redirect_url.$url;
	}
	
	
	public function thanhtoannganluong(){

		 $receiver=$_SESSION['receiver'];
		 $nganluong=$_SESSION['url_checkout'];
		 $merchantID=$_SESSION['merchantID'];
		 $mabaomat=$_SESSION['mabaomat'];
		 $return_url=$_SESSION['return_url'];
		 $affiliate_code = $_SESSION['affilatecode'];
		 $fee_cal = $_SESSION['fee_cal'];
		 unset($_SESSION['receiver']);
		 unset($_SESSION['nganluong']);
		 unset($_SESSION['merchantID']);
		 unset($_SESSION['mabaomat']);
		 unset($_SESSION['return_url']);
		 unset($_SESSION['affilatecode']);
		$order_code =$this->__($this->escapeHtml($this->getOrderId()));
		$order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
		$price = $order->getTotalDue();
		$this->__construct($nganluong,$merchantID,$mabaomat);
		return $this->buildCheckoutUrlNew($return_url, $receiver, '', $order_code, $price, $currency = 'vnd', $quantity = 1, $tax = 0, $discount = 0, $fee_cal, $fee_shipping = 0, $order_description = '', $buyer_info = '', $affiliate_code);
	}

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getNganluong()     
     { 
        if (!$this->hasData('nganluong')) {
            $this->setData('nganluong', Mage::registry('nganluong'));
        }
        return $this->getData('nganluong');
        
   	 }
}