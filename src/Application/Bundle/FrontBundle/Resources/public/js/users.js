/**
 * User class
 * 
 * @returns {Users}
 */
function Users() {

    var selfObj = this;
    var baseUrl = null;
    var organizationId = null;

    /**
     * Set the base url.
     * @param {string} base_url
     * 
     */
    this.setBaseUrl = function (base_url) {
        baseUrl = base_url;
    }

    /**
     * Set the organization id.
     * @param {string} organization_id
     * 
     */
    this.setOrganizationId = function (organization_id) {
        organizationId = organization_id;
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
                $('#orgDiv').show();
            } else {
                $('#userOrganization').removeAttr('required');
                $('#orgDiv').hide();
            }
            if (selectedRole == 'ROLE_CATALOGER' || selectedRole == 'ROLE_USER') {
                $('.projectsDiv').show();
                $('#userProjects').attr('required', 'required');
                $("#userProjects").chosen({
                    placeholder_text_multiple: "Select Projects"
                });
                $('#orgDiv').show();
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
        $("#userProjects").chosen({
                    placeholder_text_multiple: "Select Projects"
                });
    }

    this.getOrganizationProjects = function () {
        var orgId;
        if (organizationId) {
            orgId = organizationId;
        } else {
            $('#userOrganization').change(function () {
                orgId = $(this).val();
            });
        }
        if (orgId) {
            url = baseUrl + 'getOrganizationProjects/' + orgId;
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    if (response != "") {
                        $("#userProjects").html(response);
                        $("#userProjects").trigger("chosen:updated");
                    } else {
                        $("#userProjects").html("");
                        $("#userProjects").trigger("chosen:updated");
                    }
                }

            }); // Ajax Call 
        }
    }
}