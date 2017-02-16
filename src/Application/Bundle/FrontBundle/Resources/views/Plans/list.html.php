<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<?php if ($notification) { ?>
    <h1>Oops</h1>
    <div class="grid">
        <div class="row">
            Upgrade your organization to add more records. Please contact <?php echo $email ?> to upgrade.
        </div>
    </div> 
<?php } else if (!$has_admin) { ?> 
    <h1>Oops</h1>
    <div class="grid">
        <div class="row">
            Please create Admin for organization then upgrade.
        </div>
    </div>
<?php } else { ?>
    <div class="grid">
        <?php echo $view->render('ApplicationFrontBundle::Plans/_modal.html.php', array("planId" => $plan_id, "users" => $users, 'has_admin' => $has_admin, 'card' => $card)) ?>
        <div data-role="tab-control" class="tab-control">
            <ul class="tabs">
                <li id="plans" class="active"><a href="#_plans">Plans</a></li>
                <?php if (!empty($plan_id)): ?>
                    <li id="card" class=""><a href="#_card">Card Info</a></li>
                <?php endif; ?>
            </ul>
            <div class="frames">
                <div id="_plans" class="frame" style="display: block;">
                    <div class="text-alert flash-error" id="message">
                    </div>
                    <h2 style="text-align: center;">Please select a plan below</h2>
                    <div class="row">
                        <?php
                        if (!empty($entities)) {
                            ?>

                            <?php
                            $count = 1;
                            foreach ($entities as $key => $entity) {
                                if ($count == 1) {
                                    ?>
                                    <div class="listview">
                                    <?php } ?>
                                    <!--<div class="span4">-->

                                    <div class="list span4 plans">
                                        <h3><?php echo ucwords($entity->getDescription()) ?></h3>
                                        <h1><sup>$</sup><?php echo $entity->getAmount() ?><sup>/Mth</sup></h1>
                                        <?php
                                        if ($plan_id == $entity->getPlanId()) {
                                            $text = "Unsubscribe";
                                            $active = 0;
                                            if ($reactive) {
                                                $text = "Re-activate";
                                                $active = 1;
                                            }
                                            ?> 
                                            <button class="button primary large success" data-reactive="<?php echo $active ?>" id="unsubscribe"><?php echo $text ?></button>
                                        <?php } else if (!empty($plan_id)) { ?>
                                            <button class="button primary large confirm" data-plan="<?php echo $entity->getPlanId() ?>">Select Plan</button>
                                        <?php } else {
                                            ?>
                                            <button class="button primary large subscribe" data-plan="<?php echo $entity->getPlanId() ?>">Select Plan</button>
                                    <?php } ?>
                                    </div>
                                    <?php
                                    if ($count == 3) {
                                        $count = 0;
                                        ?>

                                    </div>
                                <?php } ?>
                                <?php
                                $count++;
                            }
                            ?>
                        <?php if (count($entities) % 3 != 0) { ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <h5 style="margin-left: 70px ! important">Note: Up to 2500 records are free.</h5>

            </div>
    <?php if (!empty($plan_id)): ?>
                <div id="_card" class="frame" style="display: block;">
                    <div class="row">
                        <dl class="horizontal">
                            <dt>Card No</dt>
                            <dd>
        <?php echo $card["last4"] ?>
                            </dd>
                        </dl>
                        <dl class="horizontal">
                            <dt>Expiry Date</dt>
                            <dd>
        <?php echo $card["exp_month"] ?>/<?php echo $card["exp_year"] ?>
                            </dd>
                        </dl>  
                        <button class="button primary update_card">Edit Card</button>
                    </div>
                </div>
    <?php endif; ?>
        </div>
    </div>
    </div>
<?php } ?>
<?php $view['slots']->start('view_javascripts') ?>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    Stripe.setPublishableKey('<?php echo $view->container->getParameter('stripe_publishkey') ?>');</script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/subscribe.js') ?>"></script>
<script type="text/javascript">
    function cardValidation() {
        var $form = $('#payment-form');
        $form.submit(function (event) {
            $form.find('.submit').prop('disabled', true);
            Stripe.card.createToken($form, stripeResponseHandler);
            return false;
        });

    }

    function stripeResponseHandler(status, response) {
        // Grab the form:
        var $form = $('#payment-form');

        if (response.error) { // Problem!

            // Show the errors on the form:
            $form.find('.payment-errors').text(response.error.message);
            $form.find('.submit').prop('disabled', false); // Re-enable submission
            $form.unbind('submit');
        } else { // Token was created!

            // Get the token ID:
            var token = response.id;
            // Insert the token ID into the form so it gets submitted to the server:
            $form.append($('<input type="hidden" name="stripeToken">').val(token));

            // Submit the form:
            $form.get(0).submit();
        }
    }
    $(document).ready(function () {
        var sub = new Subscribe();
        sub.setAjaxSource('<?php echo $view['router']->generate('org_admins') ?>');
        sub.setCountUrl('<?php echo $view['router']->generate('validate_plan') ?>');
        sub.setUnsubUrl('<?php echo $view['router']->generate('plan_unsub') ?>');
        sub.setUpgradeConfirmationUrl('<?php echo $view['router']->generate('plan_confirm_upgrade') ?>');
        sub.bindEvents();
    });

</script>
<?php
$view['slots']->stop();
$view['slots']->stop();
?>