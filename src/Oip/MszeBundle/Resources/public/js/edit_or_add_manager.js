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
        fun_show_save = fn["fun_show_save"],

        self = this,
        _city_id = city_id, _city_new_name, _cur_city_name,
        
        _district_id = district_id, _district_new_name, _cur_district_name,
        
        _church_id = church_id, _church_new_name,
        _cur_church_address, _cur_church_desc = '',
        _pos_lat, _pos_lng,
        
        _new_masses_list = {},
        _del_masses_list = {},
        _mod_masses_list = {},
        _smasses,
        _wmasses,
        _new_mass_id_cnt = -1,
    
        _address_setup_allow = false,
        
        _local_clear_city = function () { 
            _city_id = -1;
            
            _city_new_name = undefined;
            _cur_city_name = undefined;
        },
        
        _local_clear_district = function () {
            _district_id = -1;
            
            _district_new_name = undefined;
            _cur_district_name = undefined;
        },
        
        _local_clear_church = function () {
            _church_id = -1;
            
            _church_new_name = undefined;
            _cur_church_address = undefined;
            _cur_church_desc = undefined;
            _pos_lat = undefined;
            _pos_lng = undefined;
            
            _new_masses_list = {};
            _del_masses_list = {};
            _mod_masses_list = {};
            _smasses = undefined;
            _wmasses = undefined;
        },
        
        _local_extract_time_and_id = function(p_masses) {
            var p_ret = {};
            p_masses.forEach(function(e, ind, ar){
              var _tmp_date = new Date(e.start_time);
              var _tmp_min = _tmp_date.getMinutes() < 10 ? '0' + _tmp_date.getMinutes() : _tmp_date.getMinutes();
              
              e['hours'] = _tmp_date.getHours();
              e['minutes'] = _tmp_min;
              
              p_ret[e.id] = e;
            });
            return p_ret;
        },
        
        _local_make_array = function(_obj) {
            var ret = $.map(_obj, function(k,v) {
                return [k];
            });
            var lcompare = function(a,b) {
                var vala = parseInt(a.hours) * 100 + parseInt(a.minutes),
                    valb = parseInt(b.hours) * 100 + parseInt(b.minutes);
                if (vala < valb) { return -1; }
                else if (vala > valb) { return 1; }
                else return 0;
             }
        
            ret.sort(lcompare);
            
            return ret;
        }
        ;
    
    self.setChurchAddress = function(address) {
        _cur_church_address = address;
    }
    self.setChurchDescription = function(desc) {
        _cur_church_desc = desc;
    }
    self.setupPosition = function(lat, lng) {
        _pos_lat = lat;
        _pos_lng = lng;
    }
    self.getGoogleGPSPos = function() {
        if (_pos_lat == undefined || _pos_lng == undefined)
        {
            return undefined;
        }
        return { lat: _pos_lat, lng: _pos_lng };
    }
    
    self.getNewMassId = function()
    {
        _new_mass_id_cnt = _new_mass_id_cnt - 1;
        return _new_mass_id_cnt;
    }
    
    self.getCityId = function() { return _city_id; }
    self.getChurchId = function() { return _church_id; }
    self.getDistrictId = function() { return _district_id; }
    self.getGoogleSearch = function() 
    {      
        var _ret = '';
        if (_cur_city_name != undefined && _cur_city_name != '') {
            _ret += _cur_city_name;
        }
        /*if (_cur_district_name != undefined && _cur_district_name != '') {
            _ret += ', ' + _cur_district_name;
        }*/
        if (_cur_church_address != undefined && _cur_church_address != '') {
            _ret += ', ' + _cur_church_address;
        }
        return _ret == '' ? undefined : _ret;
    }
    
    self.renderMasses = function() {
        var n_smasses = $.extend({}, _smasses),
            n_wmasses = $.extend({}, _wmasses);
        
        for (var ind in n_smasses) {
            n_smasses[ind]['del'] = false;
            n_smasses[ind]['mod'] = false;
           if (_del_masses_list[ind] != undefined) {
               n_smasses[ind]['del'] = true;
           } else if (_mod_masses_list[ind] != undefined) {
               n_smasses[ind] = _mod_masses_list[ind];
               n_smasses[ind]['mod'] = true;
           }
        }
        
        for (ind in n_wmasses) {
            n_wmasses[ind]['del'] = false;
            n_wmasses[ind]['mod'] = false;
            if (_del_masses_list[ind] != undefined) {
               n_wmasses[ind]['del'] = true;
           } else if (_mod_masses_list[ind] != undefined) {
               n_wmasses[ind] = _mod_masses_list[ind];
               n_wmasses[ind]['mod'] = true;
           }
        }
        
        for (ind in _mod_masses_list)
        {
           if (ind < 0 && _mod_masses_list[ind]['day_sun'] == true) {
               n_smasses[ind] = _mod_masses_list[ind];
           } else if (ind < 0 && _mod_masses_list[ind]['day_sun'] == false) {
               n_wmasses[ind] = _mod_masses_list[ind];
           } 
        }
        
        
        fun_mass_hide();
        fun_mass_fill(_local_make_array(n_smasses), _local_make_array(n_wmasses))
        fun_mass_show();
    }
        
    self.setupCityId = function(id, isNew, newName, afterFun) {
        _local_clear_city()
        _local_clear_district();
        _local_clear_church();
        _city_id = id;
            
        if (id == -1 && isNew == true)
        {
            _city_new_name = newName;
            _cur_city_name = newName;
            
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
            $.oip.ajax.getJSON(('fast_city'), { id: id }, null, function(data) {
                _cur_city_name = data.name;
                fun_city_select(data.name);
                fun_district_fill(data.districts)
                fun_district_show();
                if (afterFun != undefined) { afterFun(); }
                fun_city_show_edit();
            });
        }
    }
    
    self.setupDistrictId = function(id, isNew, newName, afterFun) {
        _local_clear_district();
        _local_clear_church();
        _district_id = id;
        if (id == -100) {
            id = -1;
            newName = '';
            isNew = true;
        }
        
        if (id == -1 && isNew == true)
        {
            _district_new_name = newName;
            _cur_district_name = newName;
            
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
            $.oip.ajax.getJSON(('fast_district'), { id: id }, null, function(data) {
                _cur_district_name = data.name;
                fun_district_select(data.name);
                fun_church_fill(data.churches)
                fun_church_show();
                if (afterFun != undefined) { afterFun(); }
                if (data.name != '')
                {
                    fun_district_show_edit();
                }
            });
        }
    }
    
    self.setupChurchId = function(id, isNew, newName, afterFun) {
        _local_clear_church();
        _church_id = id;
        if (id == -1 && isNew == true)
        {
            _church_new_name = newName;
            
            _address_setup_allow = true;
            fun_church_hide_edit();
            fun_church_select(newName);
            fun_mass_clear();
            fun_mass_show();
            if (afterFun != undefined) { afterFun(); }
            fun_show_save();
        }
        else if (id == -1 && isNew !== true)
        {
            _address_setup_allow = false;
            fun_church_hide_edit();
            fun_hide_save();
            fun_church_unselect();
            fun_mass_hide();
            fun_mass_clear();
            if (afterFun != undefined) { afterFun(); }
        }
        else if (id != -1)
        {
            $.oip.ajax.getJSON(('fast_church'), { id: id }, null, function(data) {
                _cur_church_address = data.address;
                _cur_church_desc = data.description;
                _pos_lat = data.latitude;
                _pos_lng = data.longitude;
                _address_setup_allow = true;
                fun_church_select(data.name, data);
                _smasses = _local_extract_time_and_id(data.smasses);
                _wmasses = _local_extract_time_and_id(data.wmasses);
                fun_mass_fill(_local_make_array(_smasses), _local_make_array(_wmasses))
                fun_mass_show();
                if (afterFun != undefined) { afterFun(); }
                fun_church_show_edit();
            });
        }
    } 
    
    self.isMarkedForDeletion = function(id) {
        return _del_masses_list[id] != undefined;
    }
    
    self.markMassForDeletion = function(id) {
        debugger;
        if (id < 0)
        {
            delete _mod_masses_list[id];
        }
        else
        {
           _del_masses_list[id] = true; 
        }
        self.renderMasses();
    }
    
    self.unmarkMassForDeletion = function(id) {
        delete _del_masses_list[id];
        self.renderMasses();
    }
    
    self.markMassForChange = function(id, values) {
        debugger;
        _mod_masses_list[id] = values;
        self.renderMasses();
    }
    
    self.unmarkMassForChange = function(id) {
        delete _mod_masses_list[id];
        self.renderMasses();
    }
    
    self.isAddressSetupAllowed = function() {
        return _address_setup_allow;
    }
    
    self.Save = function(re_c, re_r) {
        var str = { 'cid': _city_id, 'cname': _city_new_name, 
                'did': _district_id, 'dname': _district_new_name,
                'chid': _church_id, 'chname': _church_new_name,
                'caddr': _cur_church_address,
                'cdesc': _cur_church_desc,
                'clat': _pos_lat,
                'clng': _pos_lng,
                'mmod': _mod_masses_list,
                'mdel': _del_masses_list
              };
        $.oip.ajax.postJSON('save_all', null, {'all_data': str, 're_c': re_c, 're_r': re_r}, function(data) {
            if (data['error'] != undefined) {
                alert('Error' + data.error);
                Recaptcha.reload();
            } else {
                location.href = Routing.generate('edit_or_add', {city_id: data.city_id, district_id: data.district_id, church_id: data.church_id});
            }
        });
    }
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

