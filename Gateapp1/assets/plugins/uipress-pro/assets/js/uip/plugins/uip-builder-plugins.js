///IMPORT TRANSLATIONS
const { __, _x, _n, _nx } = wp.i18n;
//Group options
export function fetchPlugins() {
  return [
    {
      label: 'UiPress Pro',
      component: 'uipProPlugin',
      path: uipProPath + 'assets/js/uip/plugins/uip-pro-plugin.min.js',
      loadInApp: true,
    },
    {
      label: 'Advanced Menu Editor',
      component: 'advanced-menu-editor',
      path: uipProPath + 'assets/js/uip/plugins/uip-advanced-menu-editor.min.js',
    },
    {
      label: 'User meta select',
      component: 'user-meta-select',
      path: uipProPath + 'assets/js/uip/plugins/uip-user-meta-select.min.js',
    },
    {
      label: 'Keyboard shortcuts',
      component: 'keyboard-shortcut',
      path: uipProPath + 'assets/js/uip/plugins/uip-keyboard-shortcut.min.js',
    },
    {
      label: 'Search post type select',
      component: 'search-post-type-select',
      path: uipProPath + 'assets/js/uip/plugins/uip-search-post-type-select.min.js',
    },
    {
      label: 'Create icons lists',
      component: 'list-item-creator',
      path: uipProPath + 'assets/js/uip/plugins/uip-list-creator.min.js',
    },
    {
      label: 'Role editor',
      component: 'uip-role-editor',
      path: uipProPath + 'assets/js/uip/tools/role-editor.min.js',
    },
    {
      label: 'UIP conditions',
      component: 'uip-conditions',
      path: uipProPath + 'assets/js/uip/plugins/uip-conditions.min.js',
    },
  ];
}
