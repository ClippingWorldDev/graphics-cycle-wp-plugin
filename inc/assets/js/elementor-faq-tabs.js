(function ($) {
  'use strict';

  var PANEL_NS = '.softroFaqTabs';
  var TABS_CONTROL_SELECTOR = '.elementor-control-softro_faq_tabs, [data-setting="softro_faq_tabs"]';
  var ITEMS_CONTROL_SELECTOR = '.elementor-control-softro_faq_items, [data-setting="softro_faq_items"]';
  var TABS_LABEL_INPUT_SELECTOR = TABS_CONTROL_SELECTOR + ' input[data-setting="tab_label"]';
  var TABS_ROW_TITLE_SELECTOR = TABS_CONTROL_SELECTOR + ' .elementor-repeater-row-title';
  var ITEM_TAB_SELECT_SELECTOR = 'select[data-setting="faq_tab_key"]';

  function debounce(fn, wait) {
    var timeoutId;

    return function () {
      var context = this;
      var args = arguments;

      clearTimeout(timeoutId);
      timeoutId = setTimeout(function () {
        fn.apply(context, args);
      }, wait);
    };
  }

  function getTabsFromModel(model) {
    if (!model || !model.get) {
      return [];
    }

    var settings = model.get('settings');

    if (!settings) {
      return [];
    }

    var tabsSetting = typeof settings.get === 'function'
      ? settings.get('softro_faq_tabs')
      : null;

    if (!tabsSetting) {
      if (settings.attributes && settings.attributes.softro_faq_tabs) {
        tabsSetting = settings.attributes.softro_faq_tabs;
      } else {
        return [];
      }
    }

    if (typeof tabsSetting === 'string') {
      try {
        tabsSetting = JSON.parse(tabsSetting);
      } catch (e) {
        return [];
      }
    }

    if (Array.isArray(tabsSetting)) {
      return tabsSetting;
    }

    if (typeof tabsSetting.toJSON === 'function') {
      return tabsSetting.toJSON();
    }

    if (tabsSetting.models && Array.isArray(tabsSetting.models)) {
      return tabsSetting.models.map(function (item) {
        if (item && typeof item.toJSON === 'function') {
          return item.toJSON();
        }

        return item && item.attributes ? item.attributes : item;
      });
    }

    if (typeof tabsSetting.each === 'function') {
      var collectionTabs = [];

      tabsSetting.each(function (item) {
        if (item && typeof item.toJSON === 'function') {
          collectionTabs.push(item.toJSON());
          return;
        }

        collectionTabs.push(item && item.attributes ? item.attributes : item);
      });

      return collectionTabs;
    }

    if (tabsSetting.attributes && tabsSetting.attributes.softro_faq_tabs) {
      return tabsSetting.attributes.softro_faq_tabs;
    }

    return [];
  }

  function getTabsFromPanel(panel) {
    if (!panel || !panel.$el) {
      return [];
    }

    var tabs = [];

    panel.$el.find(TABS_LABEL_INPUT_SELECTOR).each(function () {
      tabs.push({
        tab_label: $(this).val() || '',
      });
    });

    return tabs;
  }

  function getTabsFromPanelRowTitles(panel) {
    if (!panel || !panel.$el) {
      return [];
    }

    var tabs = [];

    panel.$el.find(TABS_ROW_TITLE_SELECTOR).each(function () {
      var title = ($(this).text() || '').trim();

      if (!title || title === '+' || title === '-') {
        return;
      }

      tabs.push({
        tab_label: title,
      });
    });

    return tabs;
  }

  function normalizeTabs(tabs) {
    if (!Array.isArray(tabs)) {
      return [];
    }

    return tabs.map(function (tab, index) {
      var label = tab && tab.tab_label ? String(tab.tab_label).trim() : '';

      if (!label) {
        label = 'Tab ' + (index + 1);
      }

      return {
        tab_label: label,
      };
    });
  }

  function pickBestTabs(modelTabs, panelInputTabs, panelTitleTabs) {
    var candidates = [
      normalizeTabs(modelTabs),
      normalizeTabs(panelTitleTabs),
      normalizeTabs(panelInputTabs),
    ];

    var best = [];

    candidates.forEach(function (tabs) {
      if (tabs.length > best.length) {
        best = tabs;
      }
    });

    return best;
  }

  function buildTabOptions(tabs) {
    var options = [{ value: '', label: 'Select Tab' }];

    if (!Array.isArray(tabs) || !tabs.length) {
      options.push({ value: 'tab_1', label: 'Tab 1' });
      return options;
    }

    tabs.forEach(function (tab, index) {
      var label = tab && tab.tab_label ? String(tab.tab_label).trim() : '';

      if (!label) {
        label = 'Tab ' + (index + 1);
      }

      options.push({
        value: 'tab_' + (index + 1),
        label: label,
      });
    });

    return options;
  }

  function optionsSignature(options) {
    return options.map(function (option) {
      return option.value + '::' + option.label;
    }).join('||');
  }

  function getSelectOptions($select) {
    var items = [];

    $select.find('option').each(function () {
      items.push({
        value: $(this).attr('value') || '',
        label: $(this).text() || '',
      });
    });

    return items;
  }

  function hasOptionValue(options, value) {
    return options.some(function (option) {
      return option.value === value;
    });
  }

  function syncFaqItemTabOptions(panel, model) {
    if (!panel || !panel.$el || !model || !model.get) {
      return;
    }

    if (model.get('widgetType') !== 'softro_faq') {
      return;
    }

    var modelTabs = getTabsFromModel(model);
    var panelInputTabs = getTabsFromPanel(panel);
    var panelTitleTabs = getTabsFromPanelRowTitles(panel);
    var tabs = pickBestTabs(modelTabs, panelInputTabs, panelTitleTabs);
    var options = buildTabOptions(tabs);
    var $selects = panel.$el.find(ITEM_TAB_SELECT_SELECTOR);

    if (!$selects.length) {
      return;
    }

    $selects.each(function () {
      var $select = $(this);
      var selectedValue = $select.val();
      var previousOptions = getSelectOptions($select);
      var previousSignature = optionsSignature(previousOptions);
      var nextSignature = optionsSignature(options);
      var shouldRebuild = previousSignature !== nextSignature;

      if (!shouldRebuild) {
        return;
      }

      $select.empty();

      options.forEach(function (option) {
        var $option = $('<option></option>').attr('value', option.value).text(option.label);

        if (selectedValue === option.value) {
          $option.prop('selected', true);
        }

        $select.append($option);
      });

      if (!hasOptionValue(options, selectedValue)) {
        $select.val('');
      } else {
        $select.val(selectedValue);
      }

      if ($select.data('select2') || $select.hasClass('e-select2-hidden-accessible')) {
        $select.trigger('change.select2');
      }
    });
  }

  function bindPanelDomSync(panel, model) {
    var syncDebounced = debounce(function () {
      syncFaqItemTabOptions(panel, model);
    }, 120);

    panel.$el.off(PANEL_NS);

    panel.$el.on(
      'input' + PANEL_NS + ' change' + PANEL_NS,
      TABS_LABEL_INPUT_SELECTOR,
      syncDebounced
    );

    panel.$el.on(
      'click' + PANEL_NS,
      TABS_CONTROL_SELECTOR + ' .elementor-repeater-add, '
        + TABS_CONTROL_SELECTOR + ' .elementor-repeater-tool-remove, '
        + TABS_CONTROL_SELECTOR + ' .elementor-repeater-tool-duplicate, '
        + ITEMS_CONTROL_SELECTOR + ' .elementor-repeater-add, '
        + ITEMS_CONTROL_SELECTOR + ' .elementor-repeater-tool-remove, '
        + ITEMS_CONTROL_SELECTOR + ' .elementor-repeater-tool-duplicate, '
        + ITEMS_CONTROL_SELECTOR + ' .elementor-repeater-row-title',
      function () {
        setTimeout(syncDebounced, 0);
      }
    );
  }

  $(window).on('elementor:init', function () {
    if (!window.elementor || !elementor.hooks) {
      return;
    }

    var onWidgetPanelOpen = function (panel, model) {
      if (!model || !model.get || model.get('widgetType') !== 'softro_faq') {
        return;
      }

      bindPanelDomSync(panel, model);

      var settings = model.get('settings');

      if (settings && settings.on && settings.off) {
        settings.off('change:softro_faq_tabs', null, model);
        settings.on('change:softro_faq_tabs', function () {
          syncFaqItemTabOptions(panel, model);
        }, model);
      }

      setTimeout(function () {
        syncFaqItemTabOptions(panel, model);
      }, 0);

      setTimeout(function () {
        syncFaqItemTabOptions(panel, model);
      }, 250);

      setTimeout(function () {
        syncFaqItemTabOptions(panel, model);
      }, 700);
    };

    elementor.hooks.addAction('panel/open_editor/widget', onWidgetPanelOpen);
    elementor.hooks.addAction('panel/open_editor/widget/softro_faq', onWidgetPanelOpen);
  });
})(jQuery);
