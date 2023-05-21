import subprocess

def install_usb_modeswitch():
    try:
        subprocess.run(['sudo', 'apt-get', 'update'], check=True)
        subprocess.run(['sudo', 'apt-get', 'install', '-y', 'usb-modeswitch'], check=True)
        print("USB modeswitch installed successfully.")
    except subprocess.CalledProcessError as e:
        print(f"An error occurred while installing USB modeswitch: {str(e)}")

if __name__ == '__main__':
    install_usb_modeswitch()
