<div class="wrap webo-admin-wrap">
    <h1>Webo Social Audit Settings</h1>
    <form method="post" action="options.php" novalidate>
        <?php
        settings_fields('webo_social_audit_settings');
        do_settings_sections('webo_social_audit_settings');
        ?>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="fb_app_id">Facebook App ID</label></th>
                    <td>
                        <input
                            id="fb_app_id"
                            type="text"
                            name="fb_app_id"
                            value="<?php echo esc_attr(get_option('fb_app_id')); ?>"
                            placeholder="Nhập Facebook App ID"
                            autocomplete="off" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="fb_app_secret">Facebook App Secret</label></th>
                    <td>
                        <input
                            id="fb_app_secret"
                            type="password"
                            name="fb_app_secret"
                            value="<?php echo esc_attr(get_option('fb_app_secret')); ?>"
                            placeholder="Nhập Facebook App Secret"
                            autocomplete="off" />
                        <p>Đảm bảo giữ bí mật thông tin này.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>