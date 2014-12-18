/**
 * User class
 * 
 * @returns {Users}
 */
function Users() {

    var selfObj = this;
    var baseUrl = null;
    
    /**
     * Set the error merge file message.
     * @param {string} merge_msg
     * 
     */
    this.setBaseUrl = function (base_url) {
        baseUrl = base_url;
    }

    /**
     * 
     * @returns {undefined}
     */
    this.bindAll = function () {
        selfObj.onChangeRole();
//        selfObj.applyChosen();
        selfObj.getOrganizationProjects();    
    }
    /**
     * 
     * @returns {undefined}
     */
    this.onChangeRole = function () {
        $('#roles').change(function () {
            var selectedRole = $(this).val();
            if (selectedRole != 'ROLE_SUPER_ADMIN') {
                $('#userOrganization').attr('required', 'required');
            } else {
                $('#userOrganization').removeAttr('required');
            }
            if (selectedRole == 'ROLE_CATALOGER' || selectedRole == 'ROLE_USER') {
                $('.projectsDiv').show();
                $('#userProjects').attr('required', 'required');
                $("#userProjects").chosen();
            } else {
                $('#userProjects').removeAttr('required');
                $('.projectsDiv').hide();
            }
        }).change();
    }
    /**
     * 
     * @returns {undefined}
     */
    this.applyChosen = function () {
        $("#userProjects").chosen();
    }

    this.getOrganizationProjects = function () {
        $('#userOrganization').change(function () {
            if ($(this).val()) {
                url = baseUrl + 'getOrganizationProjects/' + $(this).val();
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (response) {
                        if (response != "") {
                            $("#userProjects").html(response);
                            $("#userProjects").trigger("chosen:updated");
                        }else{
                            $("#userProjects").html("");
                            $("#userProjects").trigger("chosen:updated");
                        }
                    }

                }); // Ajax Call 
            }
        });
    }
}