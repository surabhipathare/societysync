///IMPORT TRANSLATIONS
const { __, _x, _n, _nx } = wp.i18n;

const uipress = new window.uipClass();
window.uipressroute = 'https://api.uipress.co/';
//Load pro blocks
import * as formBlocks from './blocks/inputs/loader.min.js?version=307';
import * as analytics from './blocks/analytics/loader.min.js?version=307';
import * as woocommerce from './blocks/storeanalytics/loader.min.js?version=307';
import * as dynamic from './blocks/dynamic/loader.min.js?version=307';
import * as elements from './blocks/elements/loader.min.js?version=307';
import * as layouts from './blocks/layout/loader.min.js?version=307';
import * as templateSettings from './settings/template-settings-groups.min.js?version=307';
import * as globalSettings from './settings/global-settings-groups.min.js?version=307';
import * as uipBuilderPlugins from './plugins/uip-builder-plugins.min.js?version=307';

//Load block settings
import * as blockSettings from './settings/builder-block-settings.min.js?version=307';
let dynamicSettings = blockSettings.getSettings(uipress.uipAppData.dynamicOptions, 'builder');
uipress.register_new_block_settings(dynamicSettings);

//Pro styles
import * as proStyles from './styles/pro-styles.min.js?version=307';
uipress.register_new_theme_styles(proStyles.fetchSettings(uipress));

let allBlocks = [].concat(woocommerce.fetchBlocks(), formBlocks.fetchBlocks(), analytics.fetchBlocks(), dynamic.fetchBlocks(), elements.fetchBlocks(), layouts.fetchBlocks());
//Import required classes and modules
uipress.register_new_blocks(allBlocks);
uipress.register_new_template_groups(templateSettings.fetchGroups());
uipress.register_new_template_groups_options(templateSettings.fetchSettings());
//Global settings
uipress.register_new_global_groups(globalSettings.fetchGroups());
uipress.register_new_global_groups_options(globalSettings.fetchSettings());
//Plugins
uipress.register_new_builder_plugins(uipBuilderPlugins.fetchPlugins());
