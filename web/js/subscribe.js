
function Subscribe() {
    var selfObj = this;
    var ajaxUrl;
    var countUrl;
    var unsubUrl;
    var upgradeConfirmationUrl;

    this.setUpgradeConfirmationUrl = function (source) {
        upgradeConfirmationUrl = source;

    };

    this.setAjaxSource = function (source) {
        ajaxUrl = source;

    };

    this.setCountUrl = function (source) {
        countUrl = source;

    };

    this.setUnsubUrl = function (source) {
        unsubUrl = source;

    };
    this.subRequest = function () {
        $('.subscribe').click(function () {
            $("#confirmationModal").hide();
            var org = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
            var plan_id = $(this).data("plan");
            $.ajax({
                type: 'POST',
                url: countUrl,
                data: {plan_id: plan_id, org_id: org},
                dataType: 'json',
                success: function (response)
                {
                    if (response.success == "true") {
                        $("#head").html("Subscribe");
                        $(".payment-errors").html("");
                        $("#plan_id").val(plan_id);
                        $("#org_id").val(org);
                        $("#subscribe_now").html('Subscribe Now');
                        $("#subscribeModal").show();
                        $("#subscribeModal").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    } else {
                        $("#message").html("<span>Total records for the organization is " + response.org_count + " but this plan allowed maximum " + response.plan_count + " records. Please choose valid plan.</span>");
                        $("#message").show();
                        setTimeout(function () {
                            $("#message").hide();
                        }, 5000);
                    }
                }

            });

        });
    };

    this.upgrateConfirmation = function () {
        $('.confirm').click(function () {
            var org = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
            var plan_id = $(this).data("plan");
            $.ajax({
                type: 'POST',
                url: upgradeConfirmationUrl,
                data: {plan_id: plan_id, org_id: org},
                dataType: 'json',
                success: function (response)
                {
                    if (response.success == "true") {
                        var html ="You will be charged $"+response.cost+". Are you sure you want to switch plan?";
                        if(response.cost < 0){
                            var charge = Math.abs(response.cost);
                            html ="You will be charged -$"+charge+". Are you sure you want to switch plan?";
                        }
                        $("#confirmation_footer .subscribe").data("plan", plan_id);
                        $("#planId").val(plan_id);
                        $("#organizationId").val(org);
                        $("#confirmation_body").html(html);
                        $("#confirmationModal").show();
                        $("#confirmationModal").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    } else {
                        $("#message").html("<span>Total records for the organization is " + response.org_count + " but this plan allowed maximum " + response.plan_count + " records. Please choose valid plan.</span>");
                        $("#message").show();
                        setTimeout(function () {
                            $("#message").hide();
                        }, 5000);
                    }
                }

            });

        });
    };

    this.orgUsers = function () {
        $('#org_id').change(function () {
            $(".user").addClass("hide");
            $("#users").prop('required', false);
            $("#users").html("");

            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {id: $(this).val()},
                dataType: 'json',
                success: function (response)
                {
                    if (response.html) {
                        $(".user").removeClass("hide");
                        $("#users").prop('required', true);
                        $("#users").html(response.html);
                    }
                }

            });
        });
    };


    this.editCardInfo = function () {
        $('.update_card').click(function () {
            var org = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
            $("#head").html("Edit Card Info");
            $(".payment-errors").html("");
            $(".users").hide();
            $("#_skip").hide();
            $("#subscribe_now").html('Update');
            $("#org_id").val(org);
            $("#subscribeModal").show();
            $("#subscribeModal").modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    };


    this.unsubRequest = function () {
        $('#unsubscribe').click(function () {
            var org = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
            $("#organization").val(org);
            var reactive = $(this).data("reactive");
            if(reactive == 1){
                $("#notification_body").html("Are you sure you want to re-activate subscription?");
            }else{
                $("#notification_body").html("Are you sure you want to unsubscribe?");
            }
            $("#reactiavte").val(reactive);
            $("#notificationModal").show();
            $("#notificationModal").modal({
                backdrop: 'static',
                keyboard: false
            });

        });
    };

    this.bindEvents = function () {
        selfObj.subRequest();
        selfObj.orgUsers();
        selfObj.editCardInfo();
        selfObj.unsubRequest();
        selfObj.upgrateConfirmation();
    };
} 