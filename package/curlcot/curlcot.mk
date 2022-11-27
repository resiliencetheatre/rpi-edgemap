CURLCOT_VERSION = 5ecbb022e067cb92394105a006e073ebead9dfaf
CURLCOT_SITE = https://codeberg.org/48554e6d/curlcot.git
CURLCOT_SITE_METHOD = git
CURLCOT_DEPENDENCIES = libcurl
CURLCOT_PREFIX = $(TARGET_DIR)/usr

define CURLCOT_BUILD_CMDS
     $(MAKE) $(TARGET_CONFIGURE_OPTS) -C $(@D)
endef

define CURLCOT_INSTALL_TARGET_CMDS
        (cd $(@D); cp curlcot $(CURLCOT_PREFIX)/bin)
endef

define CURLCOT_CLEAN_CMDS
        $(MAKE) $(CURLCOT_MAKE_OPTS) -C $(@D) clean
endef

$(eval $(generic-package))
