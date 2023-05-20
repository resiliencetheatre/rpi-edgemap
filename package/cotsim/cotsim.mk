COTSIM_VERSION = 02cd1cec5a2879163c6f51437f97447e1158e5f6
COTSIM_SITE = https://codeberg.org/48554e6d/cotsim.git
COTSIM_SITE_METHOD = git
COTSIM_DEPENDENCIES = libcurl
COTSIM_PREFIX = $(TARGET_DIR)/usr

define COTSIM_BUILD_CMDS
     $(MAKE) $(TARGET_CONFIGURE_OPTS) -C $(@D)
endef

define COTSIM_INSTALL_TARGET_CMDS
        (cd $(@D); cp cotsim $(COTSIM_PREFIX)/bin)
endef

define COTSIM_CLEAN_CMDS
        $(MAKE) $(COTSIM_MAKE_OPTS) -C $(@D) clean
endef

$(eval $(generic-package))
