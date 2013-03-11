/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$.oip = $.oip || {}
$.oip.managerDef = function(city_id, district_id, church_id, 
    fun_city_select, fun_city_unselect,
    fun_district_fill, fun_district_show, fun_district_hide, fun_district_select, fun_district_unselect,
    fun_church_show, fun_church_hide, fun_church_select, fun_church_unselect) {
        
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
        
    self.setupCityId = function(id, isNew, newName) {
        _city_id = id;
        if (id == -1 && isNew == true)
        {
            _city_new = true;
            _city_new_name = newName;
            fun_city_select(newName);
            fun_district_unselect();
            fun_district_show();
        }
        else if (id == -1 && isNew !== true)
        {
            _city_new = false;
            fun_church_hide();
            fun_district_hide();
            fun_city_unselect();
        }
        else if (id != -1)
        {
            _city_new = false;
            $.oip.ajax.get(('fast_city'), { id: id }, null, function(data) {
                fun_city_select(data.name);
                fun_district_fill(data.districts)
                fun_district_show();
            });
        }
    }
    
    self.setupDistrictId = function(id, isNew, newName) {
        _district_id = id;
    }
    
    self.setupChurchId = function(id, isNew, newName) {
        _church_id = id;
    } 
    
    //construct
    if (city_id != -1) {
        self.setupCityId(city_id);
        if (district_id != -1) {
            self.setupDistrictId(district_id);
            if (church_id != -1) {
                self.setupChurchId(church_id);
            }
        }
    }
}

