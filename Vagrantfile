# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
    config.vm.box = "laravel/homestead"
    config.vm.box_version = "9.0.0"
    config.vm.synced_folder ".", "/vagrant"

    config.vm.provision :shell, path: "vagrant/provision.sh"

    config.vm.network "private_network", ip: "192.168.10.10"
    config.vm.network "forwarded_port", guest: 22, host: 1234, host_ip: "127.0.0.1", id: 'ssh'
    config.hostsupdater.aliases = ["lemurengine.local"]


end
