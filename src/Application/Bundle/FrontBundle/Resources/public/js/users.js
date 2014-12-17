/**
 * User class
 * 
 * @returns {Users}
 */
function Users() {

    var selfObj = this;
    
    /**
     * 
     * @returns {undefined}
     */
    this.bindAll = function () {
        selfObj.onChangeRole();
        selfObj.applyChosen();
    }
    /**
     * 
     * @returns {undefined}
     */
    this.onChangeRole = function () {
        $('#application_bundle_frontbundle_users_roles').change(function () {
            var selectedRole = $(this).val();
            if (selectedRole != 'ROLE_SUPER_ADMIN') {
                $('#application_bundle_frontbundle_users_organizations').attr('required', 'required');
            } else {
                $('#application_bundle_frontbundle_users_organizations').removeAttr('required');
            }
            if (selectedRole == 'ROLE_CATALOGER') {
                $('.projectsDiv').show();
                $("#application_bundle_frontbundle_users_userProjects").trigger("chosen:updated");
                $('#application_bundle_frontbundle_users_userProjects').attr('required', 'required');
            } else {
                $('#application_bundle_frontbundle_users_userProjects').removeAttr('required');
                $('.projectsDiv').hide();
            }
        }).change();
    }
    /**
     * 
     * @returns {undefined}
     */
    this.applyChosen = function () {
        $("#application_bundle_frontbundle_users_userProjects").chosen();
    }
}