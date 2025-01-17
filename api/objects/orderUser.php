<?php
class OrderUser
{
    private $conn;
    private $list_product_info = array();

    public $order_id;
    public $user_id;
    public $order_payment_status;
    public $order_payment;
    public $web_id;
    public $order_request_id;
    public $order_trans_id;
    public $order_sum_price;
    public $order_paytype;
    public $order_datetime;
    public $order_status;
    public $order_reason;
    public $order_description = "";
    public $term;
    public $user_token;
    public $returnUrl;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function setWebID($url)
    {
        $get_url_1 = explode("//", $url);
        $get_url_2 = explode("/", $get_url_1[1]);
        $get_url_3 = explode(":", $get_url_2[0]);
        $main_url = $get_url_3[0];
        $query = "SELECT domain.domain_name, wc.* FROM domain 
                    INNER JOIN website_config wc ON wc.web_id = domain.web_id 
                                                AND domain.domain_name = :url 
                                                AND domain.domain_active = 1 
                                                AND wc.web_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':url', $main_url);
        if ($stmt->execute() === true) {
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->web_id = $result['web_id'];
                return true;
            }
            return false;
        }
        return false;
    }

    private function validateToken()
    {
        $user_token = new userToken();
        $user_token->token = $this->user_token;
        $user_token->web_id = $this->web_id;
        if ($user_token->validation() === true) {
            $this->user_id = $user_token->user_id;
            $this->user_token = $user_token->tokenId;
            return true;
        } else {
            return false;
        }
    }

    private function prepareQueryPDO($query)
    {
        $stmt = $this->conn->prepare($query);
        return $stmt;
    }

    private function removeCart()
    {
        $query = 'DELETE FROM cart WHERE user_id =:user_id AND web_id =:web_id';
        $stmt = $this->prepareQueryPDO($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':web_id', $this->web_id);
        if ($stmt->execute() === true) {
            return true;
        }
        return false;
    }

    private function getCartInformation()
    {
        $this->list_product_info = array();
        $this->order_sum_price = 0;

        $query = "SELECT * FROM cart WHERE user_id =:user_id AND web_id =:web_id";
        $stmt = $this->prepareQueryPDO($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':web_id', $this->web_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result = array(
                    'product_id'    => $row['product_id'],
                    'cart_quantity' => $row['cart_quantity'],
                    'cart_price'    => $row['cart_price']
                );
                $this->order_sum_price += floatval($row['cart_price']) * intVal($row['cart_quantity']);
                array_push($this->list_product_info, $result);
            }

            return true;
        } else {
            return false;
        }
    }

    private function createOrderCOD()
    {
        if ($this->getCartInformation() === true) {
            $this->order_id = $this->createOrderID();
            $query = 'INSERT INTO order_tb(order_id, 
                                            user_id, 
                                            order_payment_status, 
                                            order_payment, 
                                            web_id, 
                                            order_sum_price, 
                                            order_datetime, 
                                            order_status, 
                                            order_description, 
                                            order_text, 
                                            order_active)
                        VALUES(:order_id, :user_id, 1, 1, :web_id, :order_sum_price, CURRENT_TIMESTAMP(), 1, :order_description, "",1)';
            $stmt = $this->prepareQueryPDO($query);
            $stmt->bindParam(':order_id', $this->order_id);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':web_id', $this->web_id);
            $stmt->bindParam(':order_sum_price', $this->order_sum_price);
            $stmt->bindParam(':order_description', $this->order_description);
            if ($stmt->execute() === true) {
                $values = "";
                foreach ($this->list_product_info as $key => $value) {
                    $product_id = $value['product_id'];
                    $quantity = $value['cart_quantity'];
                    $unit_price = $value['cart_price'];
                    $amount = floatVal(intVal($quantity) * floatval($unit_price));
                    $values .= "('" . $this->order_id . "','" . $product_id . "','" . $quantity . "','" . $unit_price . "','" . $amount . "')";
                    if ($key != array_key_last($this->list_product_info)) {
                        $values .= ',';
                    }
                }
                $query = "INSERT INTO order_detail(order_id, product_id, order_detail_quantity, order_detail_unit_price, order_detail_amount)
                VALUES " . $values;
                $stmt_detail = $this->prepareQueryPDO($query);

                if ($stmt_detail->execute() === false) {
                    $result = array('code' => 500, 'message' => "Cannot Create COD Order Detail.");
                    return $result;
                }
                $this->removeCart();
                $result = array('code' => 200, 'message' => "COD Order success created.");
                return $result;
            } else {
                $result = array('code' => 500, 'message' => "Cannot Create COD Order .", 'data' => $stmt->debugDumpParams());
                return $result;
            }
        } else {
            $result = array('code' => 404, 'message' => "Cart's empty");
            return $result;
        }
    }

    private function createOrderMomo()
    {
        if ($this->getCartInformation() === true) {
            $this->order_id = $this->createOrderID();
            //create MOMO
            $momo = new Momo($this->order_id, "", strval($this->order_sum_price), $this->conn, $this->web_id, $this->returnUrl);
            $resultPayment = $momo->initPayment();
            $result = array('code' => 200, 'data' => $resultPayment);
            if ($resultPayment['errorCode'] === 0) {
                $query = 'INSERT INTO order_tb(order_id, user_id, order_payment_status, order_payment, web_id, order_sum_price, order_datetime, order_status, order_description, order_text, order_active)
                        VALUES(:order_id, :user_id, 1, 2, :web_id, :order_sum_price, CURRENT_TIMESTAMP(), 1, :order_description, "", 0)';
                $stmt = $this->prepareQueryPDO($query);
                $stmt->bindParam(':order_id', $this->order_id);
                $stmt->bindParam(':user_id', $this->user_id);
                $stmt->bindParam(':web_id', $this->web_id);
                $stmt->bindParam(':order_sum_price', $this->order_sum_price);
                $stmt->bindParam(':order_description', $this->order_description);
                if ($stmt->execute() === true) {
                    $values = "";
                    foreach ($this->list_product_info as $key => $value) {
                        $product_id = $value['product_id'];
                        $quantity = $value['cart_quantity'];
                        $unit_price = $value['cart_price'];
                        $amount = floatVal(intVal($quantity) * floatval($unit_price));
                        $values .= "('" . $this->order_id . "','" . $product_id . "','" . $quantity . "','" . $unit_price . "','" . $amount . "')";
                        if ($key != array_key_last($this->list_product_info)) {
                            $values .= ',';
                        }
                    }
                    $query = "INSERT INTO order_detail(order_id, product_id, order_detail_quantity, order_detail_unit_price, order_detail_amount)
                                VALUES " . $values;
                    $stmt_detail = $this->prepareQueryPDO($query);

                    if ($stmt_detail->execute() === false) {
                        $result = array('code' => 500, 'message' => "Cannot Create Order Detail MOMO method payment.");
                        return $result;
                    }
                    $result = array('code' => 200, 'data' => $resultPayment);
                    return $result;
                }
                $result = array('code' => 500, 'message' => 'Cannot create order');
                return $result;
            } else {
                $result = array('code' => 200, 'message' => "Cannot Create MOMO payment method.", 'data' => $resultPayment);
                return $result;
            }
        } else {
            $result = array('code' => 404, 'message' => "Cart's empty");
            return $result;
        }
    }

    private function createOrderID()
    {
        $id = strtoupper(uniqid('ORDER'));
        while (true) {
            $query = "SELECT order_id FROM order_tb WHERE order_id =:order_id";
            $stmt = $this->prepareQueryPDO($query);
            $stmt->bindParam(':order_id', $id);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                break;
            }
        }
        return $id;
    }

    public function createOrder()
    {
        if ($this->validateToken() === true) {
            $result = array();
            switch ($this->order_payment) {
                case 1:
                    $result = $this->createOrderCOD();
                    break;
                case 2:
                    $result = $this->createOrderMomo();
                    break;
                case 3:
                    $result = array('code' => 400, 'message' => 'No Payment Method Choosed');
            }
            return $result;
        } else {
            $result = array('code' => 403, 'message' => 'Token Expired');
            return $result;
        }
    }

    public function getOrderByUser( $orderCancel = false ){
        if($this->validateToken() === true){
            if($orderCancel){
                $query = "SELECT * FROM order_tb WHERE user_id = :user_id AND web_id = :web_id AND (order_status = 3 OR order_status = 5) AND order_active = 1 ORDER BY order_tb.order_datetime DESC";
            }
            else{
                $query = "SELECT * FROM order_tb WHERE user_id = :user_id AND web_id = :web_id AND order_status = ".$this->order_status." AND order_active = 1 ORDER BY order_tb.order_datetime DESC";
            }
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id",      $this->user_id);
            $stmt->bindParam(":web_id",       $this->web_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        }
    }

    public function getOrderDetail(){
        $query = "SELECT order_detail.*, 
                  product.product_name, 
                  product.product_currency,
                  product.product_image_path 
                  FROM order_detail 
                  INNER JOIN order_tb ON order_detail.order_id = order_tb.order_id AND order_tb.order_active =1
                  INNER JOIN product  ON order_detail.product_id = product.product_id 
                  AND order_detail.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->execute();
        return $stmt;
    }

    public function cancelOrderUser(){
        $message ='';
        if($this->validateToken() === true){
            $query ="UPDATE order_tb SET order_status = 5, order_reason = 3 WHERE order_id = :order_id AND web_id = :web_id AND user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":order_id", $this->order_id);
            $stmt->bindParam(":user_id",  $this->user_id);
            $stmt->bindParam(":web_id",   $this->web_id, PDO::PARAM_INT);
            if($stmt->execute() === true){
                $message = true;
                return $message;
            }
            else{
                $message = 'Do Not Cancel Order!!';
                return $message;
            }
        }
        else{
            $message = 'Something has wrong!';
            return $message;
        }
    }
    
    private function checkPaymentOfOrder(){
        $query = "SELECT order_payment, order_status FROM order_tb WHERE order_id =:order_id AND order_status = 1 AND web_id =:web_id";
        $stmt = $this->prepareQueryPDO($query);
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->bindParam(':web_id', $this->web_id);
        $stmt->execute();
        if ($stmt->rowCount() === 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->order_payment = $result['order_payment'];
            $this->order_status = $result['order_status'];
            return true;
        }
        return false;
    }

    //Khời tạo yêu cầu hủy, hủy đơn trực tiếp với đơn hàng cod
    private function requestCancelCodOrder()
    {
        $query =  "UPDATE order_tb SET order_status = 3, order_reason = 3 WHERE order_id =:order_id";
        $stmt = $this->prepareQueryPDO($query);
        $stmt->bindParam(':order_id', $this->order_id);
        if ($stmt->execute() === true) {
            return true;
        }
        return false;
    }

    //Khởi tạo yêu cầu hủy đơn, chuyển order_status = 5
    private function requestCancelMomoOrder()
    {
        $query =  "UPDATE order_tb SET order_status = 5, order_reason = 3 WHERE order_id =:order_id";
        $stmt = $this->prepareQueryPDO($query);
        $stmt->bindParam(':order_id', $this->order_id);
        if ($stmt->execute() === true) {
            return true;
        }
        return false;
    }

    public function createRequestCancel()
    {
        if ($this->validateToken() === true) {
            if ($this->checkPaymentOfOrder() === true) {
                $result = array();
                if ($this->order_status == 1) {
                    switch ($this->order_payment) {
                        case 1:
                            if ($this->requestCancelCodOrder() === true) {
                                $result = array('code' => 200, 'message' => 'Request has sent');
                            } else {
                                $result = array('code' => 1002, 'message' => 'Order got error while canceling');
                            }
                            break;
                        case 2:
                            if ($this->requestCancelMomoOrder() === true) {
                                $result = array('code' => 200, 'message' => 'Request has sent');
                            } else {
                                $result = array('code' => 1002, 'message' => 'Order got error while canceling');
                            }
                            break;
                    }
                    return $result;
                } else {
                    $result = array('code' => 1003, 'message' => 'Order could not be canceled. This order has been excepted.');
                    return $result;
                }
            } else {
                $result = array('code' => 1001, 'message' => 'Order does not have permission');
                return $result;
            }
        } else {
            $result = array('code' => 403, 'message' => 'Token Expired');
            return $result;
        }
    }
}
