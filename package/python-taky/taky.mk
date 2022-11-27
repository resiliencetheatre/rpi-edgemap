################################################################################
#
# python-taky
#
################################################################################

PYTHON_TAKY_VERSION = 0.8.4
PYTHON_TAKY_SOURCE = taky-$(PYTHON_TAKY_VERSION).tar.gz
# PYTHON_TAKY_SITE = https://files.pythonhosted.org/packages/8f/c1/c564609ab7e0e0ccca4f2dd44e9dc436c0c4dbaf5253640078c209929786
PYTHON_TAKY_SITE = https://files.pythonhosted.org/packages/6b/4e/946341e54ca908b23f7d1835444398e40bf4caaa328b17a6bae6d91aaa68
PYTHON_TAKY_SETUP_TYPE = setuptools
PYTHON_TAKY_LICENSE = MIT
PYTHON_TAKY_LICENSE_FILES = LICENSE

$(eval $(python-package))
