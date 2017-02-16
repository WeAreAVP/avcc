<div id="subscribeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="subscribeModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <h4 id="head">Subscribe</h4>
            </div>
            <div id="beforeExport">
                <form action="<?php echo $view['router']->generate('plan_sub') ?>" method="POST" id="payment-form" class="form-horizontal">
                    <input type="hidden" id="plan_id" name="plan_id" value="">
                    <input type="hidden" id="org_id" name="org_id" value="">
                    <div class="modal-body">
                        <span class="text-alert flash-error payment-errors"></span>
                        <br>
                        <?php if ($view['security']->isGranted('ROLE_SUPER_ADMIN') && !empty($users)): ?>
                            <label class="users">Users</label>
                            <div class="input-control" data-role="input-control">
                                <select id="user_id" name="user_id" class="size4 users" required="true">
                                    <option value=""></option>
                                    <?php foreach ($users as $user) { ?>
                                        <option value="<?php echo $user->getId() ?>"><?php echo $user->getName() ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <label>Card Number</label>
                        <div class="input-control text" data-role="input-control">
                            <input type="text" size="20" data-stripe="number" class="size4"><?php if (!empty($card)) { ?> <span>(<?php echo $card["last4"] ?>)</span> <?php } ?>
                        </div>

                        <label>Expiration (MM/YY)</label>
                        <div class="input-control text" data-role="input-control">
                            <input type="text" size="2" data-stripe="exp_month" class="size1"> <span> / </span>
                            <input type="text" size="2" class="size1" data-stripe="exp_year">
                        </div>

                        <label>CVC</label>
                        <div class="input-control text" data-role="input-control">
                            <input type="text" size="4" data-stripe="cvc" class="size2">
                        </div>
                    </div>
                    <div class="modal-footer" id="modal-footer">
                        <button type="button" name="close" id="close" class="button closeModal" data-dismiss="modal">No</button> &nbsp;
                        <button class="button primary submit" id="subscribe_now" onclick="cardValidation()">Subscribe Now</button>
                        <?php if ($planId) { ?>
                            &nbsp; <input type="submit" class="button primary submit" id="_skip" value="Skip And Subscribe">
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="notificationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <h3 id="myModalLabel">UnSubscribe</h3>
            </div>
            <div id="notification_process">
                <form action="<?php echo $view['router']->generate('plan_unsub') ?>" method="POST" class="form-horizontal">
                    <input type="hidden" id="organization" name="org_id" value="">
                    <input type="hidden" id="reactiavte" name="reactive" value="0">
                    <div class="modal-body" id="notification_body" style="font-size: 12px;">
                        Are you sure you want to unsubscribe?
                    </div>
                    <div class="modal-footer" id="notification_footer">
                        <button type="button" name="close" id="close" class="button closeModal" data-dismiss="modal">No</button> &nbsp;
                        <input type="submit" class="button primary submit" value="Yes">
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>

<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true" style="display:none;">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class="modal-header">
                <h3>Confirmation</h3>
            </div>
            <div id="confirmation_process">
                <input type="hidden" id="organizationId" name="org_id" value="">
                <input type="hidden" id="planId" name="plan_id" value="">
                <div class="modal-body" id="confirmation_body" style="font-size: 12px;">
                    Are you sure you want to unsubscribe?
                </div>
                <div class="modal-footer" id="confirmation_footer">
                    <button type="button" name="close" id="close" class="button closeModal" data-dismiss="modal">No</button> &nbsp;
                    <button class="button primary subscribe">Upgrade Now</button>
                </div>
            </div> 
        </div>
    </div>
</div>