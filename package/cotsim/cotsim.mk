COTSIM_VERSION = 9a3be9d1949f69d39ba2d12311092d71ce994114
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
