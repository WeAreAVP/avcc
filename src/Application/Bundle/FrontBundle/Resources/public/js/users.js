/**
 * User class
 * 
 * @returns {Users}
 */
function Users() {
    
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
        }).change();
    }
}