///IMPORT TRANSLATIONS
const { __, _x, _n, _nx } = wp.i18n;
export function fetchGroups() {
  return {
    //Group two
    whiteLabel: {
      label: __('White label', 'uipress-pro'),
      name: 'whiteLabel',
      icon: 'branding_watermark',
    },
    analytics: {
      label: __('Analytics', 'uipress-pro'),
      name: 'analytics',
      icon: 'bar_chart',
    },
  };
}

//Group options
export function fetchSettings() {
  return [
    //White label
    {
      component: 'image-select',
      group: 'whiteLabel',
      uniqueKey: 'favicon',
      label: __('Template favicon', 'uipress-pro'),
      accepts: Object,
      args: {
        language: 'css',
      },
    },
    {
      component: 'uip-dynamic-input',
      group: 'whiteLabel',
      uniqueKey: 'siteTitle',
      label: __('Replace Wordpress in site title', 'uipress-pro'),
      accepts: Object,
      args: {
        language: 'css',
      },
    },
    //Analytics
    {
      component: 'switch-select',
      group: 'analytics',
      uniqueKey: 'saveAccountToUser',
      label: __('Google Analtics account set on user level', 'uipress-pro'),
      help: __('By default, accounts are set site wide. So whoever logs in sees the same account data. Setting this on a user level allows each user to sync their own account', 'uipress-pro'),
      accepts: Boolean,
      args: {
        language: 'css',
      },
    },
  ];
}
