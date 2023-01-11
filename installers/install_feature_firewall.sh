function _install_feature_firewall() {
    name="feature firewall"

    _install_log "Install $name"
	# create config dir
	sudo mkdir "$raspap_network/firewall" || _install_status 1 "Unable to create firewall config directory" 
    # copy firewall configuration 
    sudo cp "$webroot_dir/config/iptables_rules.json" "$raspap_network/firewall/" || _install_status 1 "Unable to copy iptables templates"
	sudo chown $raspap_user:$raspap_user -R "$raspap_network/firewall" || _install_status 1 "Unable to change ownership of firewall directory and files "
    _install_status 0
}
