# Edgemap firmware

External tree for [buildroot](https://buildroot.org) to build RaspberryPi4 based Edgemap firmware image. This firmware boots on RPi4 and acts as [taky](https://github.com/tkuester/taky) server with Web UI. 

## Building

To build Edgemap firmware, you need to install Buildroot environment and clone this repository as 'external tree' to buildroot. Make sure you check buildroot manual for required packages for your host, before building.

For Fedora you can install these dependencies:

```
sudo dnf group install "C Development Tools and Libraries" "Development Tools"
sudo dnf install cmake ncurses-devel git perl
```

Create build directory and clone repositories:

```
mkdir ~/build-directory
cd ~/build-directory
git clone https://git.buildroot.net/buildroot
cd buildroot
git checkout 2023.02
cd ~/build-directory
git clone https://github.com/resiliencetheatre/rpi-edgemap.git
```

You might need to checkout LTS (or e3c14de0dfc047817917fb4dbc20c211e751bb3f) version of buildroot if you face build difficulties.

Define _external tree_ location to **BR2_EXTERNAL** variable:

```
export BR2_EXTERNAL=~/build-directory/rpi-edgemap
```

Make Edgemap defconfig and start building:

```
cd ~/build-directory/buildroot
make raspberrypi4_64_edgemap_defconfig
make
```

After build is completed, you find image file for MicroSD card at:

```
~/build-directory/buildroot/output/images/sdcard.img
```

Use 'dd' to copy this image to your MicroSD card.

Please note that you need to create third partition to MicroSD card and link required mbtiles after logging in to RPi4:

```
cd /opt/edgemap/edgemap-webui
ln -s /mnt/mbtiles/us.mbtiles .
```

Third partition is automaticly mounted (as read only) to /mnt

## Support the Arabic and Hebrew languages righ-to-left rendering

First clone this repository:

```
git clone https://github.com/mapbox/mapbox-gl-rtl-text
```

Copy mapbox-gl-rtl-text.js to /opt/edgemap/edgemap-webui/js/ folder.

