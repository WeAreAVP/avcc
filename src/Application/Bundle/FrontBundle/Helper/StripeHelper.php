<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */

namespace Application\Bundle\FrontBundle\Helper;

use Doctrine\ORM\EntityManager;
use Application\Bundle\FrontBundle\Entity\Plans;

class StripeHelper {

    private $em;

    public function __construct($container) {
        $stripe_secretkey = $container->getParameter('stripe_secretkey');
        \Stripe\Stripe::setApiKey($stripe_secretkey);
    }

    public function createPlan($data) {
        try {
            $response = \Stripe\Plan::create(array(
                        "amount" => (int) $data["amount"] * 100,
                        "interval" => $data["interval"],
                        "name" => $data["name"],
                        "currency" => "usd",
                        "id" => $data["id"],
                        "statement_descriptor" => $data["desc"]
                            )
            );
            return true;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function updatePlan($data) {
//        $this->setAPIKey();
        try {
            $p = \Stripe\Plan::retrieve($data["id"]);
            $p->name = $data["name"];
            $p->statement_descriptor = $data["desc"];
            $p->save();
            return true;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function deletePlan($id) {
        try {
            $plan = \Stripe\Plan::retrieve($id);
            $plan->delete();
            return true;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    private function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    public function createAndSubscribeCustomer($data) {
        try {
            if (isset($data["token"]) && $data["customerId"] != "" && !empty($data["customerId"])) {
                $cus = \Stripe\Customer::retrieve($data["customerId"]);
                $_customr = json_decode(json_encode($cus), TRUE);
                $card = $_customr["sources"]["data"][0]["id"];
                $cus->sources->retrieve($card)->delete();
                $cus->source = $data["token"];
                $cus->save();
                if ($data["subId"] != "" && !empty($data["subId"])) {

                    $result = $this->retrieveAndUpdateSubscription($data["subId"], $data["plan_id"], $data["proration_date"]);
                    if (!$result) {
                        return $result;
                    }
                }
                return json_decode(json_encode($cus), true);
            } else if ($data["customerId"] != "" && !empty($data["customerId"])) {
                if ($data["subId"] != "" && !empty($data["subId"])) {
                    $result = $this->retrieveAndUpdateSubscription($data["subId"], $data["plan_id"], $data["proration_date"]);
                    if (!$result) {
                        return $result;
                    }
                }
                $cus = \Stripe\Customer::retrieve($data["customerId"]);
                return json_decode(json_encode($cus), true);
            } else {
                $customer = \Stripe\Customer::create(array(
                            "source" => $data["token"], // obtained from Stripe.js
                            "plan" => $data["plan_id"],
                            "email" => $data["email"]
                ));
                return json_decode(json_encode($customer), true);
            }
        } catch (\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\RateLimit $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function cancelSubscription($id, $end_at = false) {
        try {
            $sub = \Stripe\Subscription::retrieve($id);
            if ($end_at) {
                $sub->cancel(array('at_period_end' => true));
                return json_decode(json_encode($sub), TRUE);
            } else {
                $sub->cancel();
            }
            return true;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function getCardInfo($cus_id) {
        try {
            $cus = \Stripe\Customer::retrieve($cus_id);
            $_customr = json_decode(json_encode($cus), TRUE);
            $card = $_customr["sources"]["data"][0];
            return $card;
        } catch (\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\RateLimit $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function updateCardInfo($cus_id, $token) {
        try {
            $cus = \Stripe\Customer::retrieve($cus_id);
            $_customr = json_decode(json_encode($cus), TRUE);
            $card = $_customr["sources"]["data"][0]["id"];
            $cus->sources->retrieve($card)->delete();
            $cus->source = $token;
            $cus->save();
            return json_decode(json_encode($cus), true);
        } catch (\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\RateLimit $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function deleteCustomer($cus_id) {
        try {
            $cus = \Stripe\Customer::retrieve($cus_id);
            $cus->delete();
            return true;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function retrieveAndUpdateSubscription($id, $planId, $proration_date) {

        try {
            $sub = \Stripe\Subscription::retrieve($id);
            $_subsciption = json_decode(json_encode($sub), TRUE);
            $current_plan = $_subsciption["plan"]["id"];
            if ($current_plan != $planId) {
                $sub->plan = $planId;
                if ($proration_date != "")
                    $sub->proration_date = $proration_date;
                $sub->save();
            }
            return true;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function prorationPreview($data) {

        try {
            $proration_date = $data["proration_date"];
            $invoice = \Stripe\Invoice::upcoming(array(
                        "customer" => $data["cutomerId"],
                        "subscription" => $data["subId"],
                        "subscription_plan" => $data["planId"], # Switch to new plan
                        "subscription_proration_date" => $proration_date
            ));
            $cost = 0;
            $current_prorations = array();
            foreach ($invoice->lines->data as $line) {
                if ($line->period->start == $proration_date) {
                    array_push($current_prorations, $line);
                    $cost += $line->amount;
                }
            }
            if ($cost > 0 || $cost < 0) {
                $cost = $cost / 100;
            }
            return $cost;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function retrieveSubscription($id) {

        try {
            $sub = \Stripe\Subscription::retrieve($id);
            $_subsciption = json_decode(json_encode($sub), TRUE);
            return $_subsciption;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function getCustomer($cus_id) {
        try {
            $cus = \Stripe\Customer::retrieve($cus_id);
            return json_decode(json_encode($cus), TRUE);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

    public function updateSubscription($id, $planId) {
        try {
            $sub = \Stripe\Subscription::retrieve($id);
            $sub->plan = $planId;
            $sub->save();
            return json_decode(json_encode($sub), TRUE);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }
    
    public function updateCharge($data) {
        try {
            $p = \Stripe\Charge::retrieve($data["id"]);
            $p->receipt_email = $data["emails"];
            $p->save();
            return true;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return $err['message'];
        }
    }

}
