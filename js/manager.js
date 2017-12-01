'use strict';

module.exports = function (oAppData) {
	var App = require('%PathToCoreWebclientModule%/js/App.js');
	
	if (App.getUserRole() === Enums.UserRole.NormalUser || App.getUserRole() === Enums.UserRole.Customer)
	{
		var
			TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),

			Settings = require('modules/%ModuleName%/js/Settings.js'),
			
			HeaderItemView = null
		;

		Settings.init(oAppData);

		return {
			enableModule: Settings.enableModule,

			/**
			 * Registers settings tab of simple chat module before application start.
			 * 
			 * @param {Object} ModulesManager
			 */
			start: function (ModulesManager) {
				ModulesManager.run('SettingsWebclient', 'registerSettingsTab', [function () { return require('modules/%ModuleName%/js/views/SimpleChatSettingsFormView.js'); }, Settings.HashModuleName, TextUtils.i18n('%MODULENAME%/LABEL_SETTINGS_TAB')]);
			},

			/**
			 * Returns list of functions that are return module screens.
			 * 
			 * @returns {Object}
			 */
			getScreens: function () {
				var oScreens = {};
				oScreens[Settings.HashModuleName] = function () {
					return require('modules/%ModuleName%/js/views/MainView.js');
				};
				return oScreens;
			},

			/**
			 * Returns object of header item view of simple chat module.
			 * 
			 * @returns {Object}
			 */
			getHeaderItem: function () {
				if (HeaderItemView === null)
				{
					var CHeaderItemView = require('%PathToCoreWebclientModule%/js/views/CHeaderItemView.js');
					HeaderItemView = new CHeaderItemView(TextUtils.i18n('%MODULENAME%/ACTION_SHOW_CHAT'));
				}

				return {
					item: HeaderItemView,
					name: Settings.HashModuleName
				};
			}
		};
	}
	
	return null;
};
