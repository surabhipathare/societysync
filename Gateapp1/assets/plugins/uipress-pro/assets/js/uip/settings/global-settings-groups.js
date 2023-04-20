///IMPORT TRANSLATIONS
const { __, _x, _n, _nx } = wp.i18n;
///Groups
export function fetchGroups() {
  return {
    advanced: {
      label: __('Advanced', 'uipress-pro'),
      name: 'advanced',
      icon: 'code',
    },
    whiteLabel: {
      label: __('White label', 'uipress-pro'),
      name: 'whiteLabel',
      icon: 'branding_watermark',
    },

    login: {
      label: __('Login', 'uipress-pro'),
      name: 'login',
      icon: 'login',
    },
  };
}
//Group options
export function fetchSettings() {
  return [
    {
      component: 'switch-select',
      group: 'whiteLabel',
      uniqueKey: 'hidePlugins',
      label: __('Hide UiPress from plugin table', 'uipress-pro'),
      help: __('If enabled, both UiPress lite and pro (if installed) will be hidden from the plugins table', 'uipress-pro'),
      accepts: Boolean,
    },

    //Advanced
    {
      component: 'switch-select',
      group: 'advanced',
      uniqueKey: 'addRoleToBody',
      label: __('Add user roles as body class', 'uipress-pro'),
      help: __('If enabled, the current user roles will be added as classes to the admin body tag. This can give you more flexibility in your css for role based conditions', 'uipress-pro'),
      accepts: Boolean,
      order: 2,
    },
    {
      component: 'array-list',
      group: 'advanced',
      uniqueKey: 'enqueueStyles',
      label: __('Enqueue styles', 'uipress-pro'),
      help: __('Add stylesheets to the head of every admin page', 'uipress-pro'),
      accepts: Array,
      order: 3,
    },
    //Advanced
    {
      component: 'array-list',
      group: 'advanced',
      uniqueKey: 'enqueueScripts',
      label: __('Enqueue scripts', 'uipress-pro'),
      help: __('Add scripts to the head of every admin page', 'uipress-pro'),
      accepts: Array,
      order: 4,
    },

    //Advanced
    {
      component: 'code-editor',
      group: 'advanced',
      uniqueKey: 'htmlHead',
      label: __('HTML for head', 'uipress-pro'),
      help: __('Add HTML here to be added to every admin page head section', 'uipress-pro'),
      accepts: String,
      args: {
        language: 'html',
      },
      order: 5,
    },

    //login
    {
      component: 'switch-select',
      group: 'login',
      uniqueKey: 'darkMode',
      label: __('Dark mode', 'uipress-pro'),
      help: __('Forces dark mode on the login page', 'uipress-pro'),
      accepts: Boolean,
      order: 6,
    },
    {
      component: 'switch-select',
      group: 'login',
      uniqueKey: 'hideLanguage',
      label: __('Disable language selector', 'uipress-pro'),
      help: __('Disables the language selector on the login page', 'uipress-pro'),
      order: 7,
    },
    {
      component: 'switch-select',
      group: 'login',
      uniqueKey: 'removeBranding',
      label: __('Remove UiPress link', 'uipress-pro'),
      help: __('Removes the powered by uipress link', 'uipress-pro'),
      order: 8,
    },
    {
      component: 'code-editor',
      group: 'login',
      uniqueKey: 'panelHTML',
      label: __('Custom HTML', 'uipress-lite'),
      help: __('HTML to be added to the side panel of the login page. Only works when the login theme is enabled and not using the centered form.', 'uipress-lite'),
      accepts: String,
      order: 9,
      args: {
        language: 'html',
      },
    },
    {
      component: 'code-editor',
      group: 'login',
      uniqueKey: 'loginCSS',
      label: __('Custom CSS', 'uipress-lite'),
      help: __('CSS to be added to the login page', 'uipress-lite'),
      accepts: String,
      order: 10,
      args: {
        language: 'css',
      },
    },

    ///Media
    {
      component: 'switch-select',
      group: 'media',
      uniqueKey: 'privateLibrary',
      label: __('Private library', 'uipress-lite'),
      help: __('If enabled, users will only be able to view their own media in the media library. This does not apply to administrators', 'uipress-lite'),
      accepts: Boolean,
    },

    ///Media
    {
      component: 'switch-select',
      group: 'postsPages',
      uniqueKey: 'privatePosts',
      label: __('Private posts', 'uipress-lite'),
      help: __('If enabled, users will only be able to view their own posts and pages in the post tables. This does not apply to administrators', 'uipress-lite'),
      accepts: Boolean,
    },
  ];
}
