/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$.oip = $.oip || {}
$.oip.managerDef = function(city_id, district_id, church_id, fn) {
    var fun_city_select = fn["fun_city_select"],
        fun_city_unselect = fn["fun_city_unselect"],
        fun_city_show_edit = fn["fun_city_show_edit"],
        fun_city_hide_edit = fn["fun_city_hide_edit"],
        
        fun_district_fill = fn["fun_district_fill"],
        fun_district_show = fn["fun_district_show"],
        fun_district_hide = fn["fun_district_hide"],
        fun_district_select = fn["fun_district_select"],
        fun_district_unselect = fn["fun_district_unselect"],
        fun_district_clear = fn["fun_district_clear"],
        fun_district_show_edit = fn["fun_district_show_edit"],
        fun_district_hide_edit = fn["fun_district_hide_edit"],
        
        fun_church_fill = fn["fun_church_fill"],
        fun_church_show = fn["fun_church_show"],
        fun_church_hide = fn["fun_church_hide"],
        fun_church_select = fn["fun_church_select"],
        fun_church_unselect = fn["fun_church_unselect"],
        fun_church_clear = fn["fun_church_clear"],
        fun_church_show_edit = fn["fun_church_show_edit"],
        fun_church_hide_edit = fn["fun_church_hide_edit"],
        
        fun_mass_fill = fn["fun_mass_fill"],
        fun_mass_show = fn["fun_mass_show"],
        fun_mass_hide = fn["fun_mass_hide"],
        fun_mass_clear = fn["fun_mass_clear"],
        
        fun_hide_save = fn["fun_hide_save"],
        fun_show_save = fn["fun_show_save"];

    var self = this;
    var _city_id = city_id;
    var _district_id = district_id;
    var _church_id = church_id;
    
    var _city_new, _city_new_name;
    var _district_new, _district_new_name;
    var _church_new, _church_new_name;
    
    self.getCityId = function() { return _city_id; }
    self.getChurchId = function() { return _church_id; }
    self.getDistrictId = function() { return _district_id; }
        
    self.setupCityId = function(id, isNew, newName, afterFun) {
        _city_id = id;
        _district_id = -1;
        _church_id = -1;
        if (id == -1 && isNew == true)
        {
            _city_new = true;
            _city_new_name = newName;
            fun_city_hide_edit();
            fun_district_hide_edit();
            fun_church_hide_edit();
            
            fun_city_select(newName);
            fun_district_unselect();
            fun_district_show();
            fun_district_clear();
            
            fun_church_unselect();
            fun_church_hide();
            fun_church_clear();
            
            fun_mass_hide();
            fun_mass_clear();
            
            if (afterFun != undefined) { afterFun(); }
            
            fun_show_save();
        }
        else if (id == -1 && isNew !== true)
        {
            _city_new = false;
            fun_city_hide_edit();
            fun_district_hide_edit();
            fun_church_hide_edit();
            fun_hide_save();
            fun_mass_clear();
            fun_mass_hide();
            fun_church_unselect();
            fun_church_hide();
            fun_district_unselect();
            fun_district_hide();
            fun_city_unselect();
            if (afterFun != undefined) { afterFun(); }
        }
        else if (id != -1)
        {
            _city_new = false;
            $.oip.ajax.getJSON(('fast_city'), { id: id }, null, function(data) {
                fun_city_select(data.name);
                fun_district_fill(data.districts)
                fun_district_show();
                if (afterFun != undefined) { afterFun(); }
                fun_city_show_edit();
            });
        }
    }
    
    self.setupDistrictId = function(id, isNew, newName, afterFun) {
        _district_id = id;
        _church_id = -1;
        if (id == -100) {
            id = -1;
            newName = '';
            isNew = true;
        }
        
        if (id == -1 && isNew == true)
        {
            _district_new = true;
            _district_new_name = newName;
            fun_district_hide_edit();
            fun_church_hide_edit();
            fun_district_select(newName);
            fun_church_unselect();
            fun_church_show();
            fun_church_clear();
            fun_mass_hide();
            fun_mass_clear();
            if (afterFun != undefined) { afterFun(); }
            fun_show_save();
        }
        else if (id == -1 && isNew !== true)
        {
            _district_new = false;
            fun_district_hide_edit();
            fun_church_hide_edit();
            fun_hide_save();
            fun_mass_hide();
            fun_mass_clear();
            fun_church_unselect();
            fun_church_hide();
            fun_district_unselect();
            if (afterFun != undefined) { afterFun(); }
        }
        else if (id != -1)
        {
            _district_new = false;
            $.oip.ajax.getJSON(('fast_district'), { id: id }, null, function(data) {
                fun_district_select(data.name);
                fun_church_fill(data.churches)
                fun_church_show();
                if (afterFun != undefined) { afterFun(); }
                fun_district_show_edit();
            });
        }
    }
    
    self.setupChurchId = function(id, isNew, newName, afterFun) {
        _church_id = id;
        if (id == -1 && isNew == true)
        {
            _church_new = true;
            _church_new_name = newName;
            fun_church_hide_edit();
            fun_church_select(newName);
            fun_mass_clear();
            fun_mass_show();
            if (afterFun != undefined) { afterFun(); }
            fun_show_save();
        }
        else if (id == -1 && isNew !== true)
        {
            _church_new = false;
            fun_church_hide_edit();
            fun_hide_save();
            fun_church_unselect();
            fun_mass_hide();
            fun_mass_clear();
            if (afterFun != undefined) { afterFun(); }
        }
        else if (id != -1)
        {
            _church_new = false;
            $.oip.ajax.getJSON(('fast_church'), { id: id }, null, function(data) {
                fun_church_select(data.name);
                fun_mass_fill(data.masses)
                fun_mass_show();
                if (afterFun != undefined) { afterFun(); }
                fun_church_show_edit();
            });
        }
    } 
    
    self.Save = function() {
        var str = { 'cid': _city_id, 'cname': _city_new_name, 
                'did': _district_id, 'dname': _district_new_name,
                'chid': _church_id, 'chname': _church_new_name
              };
        
        $.oip.ajax.postJSON('save_all', null, {'all_data': str}, function(data) {
            
            debugger;
            if ($.inArray('error', data) == true) {
                alert('Error' + data.error);
            } else {
                location.href = Routing.generate('edit_or_add', {city_id: data.city_id, district_id: data.district_id, church_id: data.church_id});
            }
        });
    }
    debugger;
    //construct
    if (city_id != -1) {
        self.setupCityId(city_id, undefined, undefined, function() {
            if (district_id != -1) {
                self.setupDistrictId(district_id, undefined, undefined, function() {
                    if (church_id != -1) {
                        self.setupChurchId(church_id);
                    }
                });
            }
        });
    }
}

