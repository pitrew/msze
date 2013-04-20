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
        
        fun_district_default_set = fn["fun_district_default_set"],
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
        _city = {},
        _district = {},
        _church = { },
        _mass = { 
            del_list: {},
            mod_list: {}
        },
        
        _new_mass_id_cnt = -1,
        _address_setup_allow = false,
        
        _local_clear_city = function () { 
            _city = { id: -1 };
        },
        
        _local_clear_district = function () {
            _district = { id: -1 };
        },
        
        _local_clear_church = function () {
            _church = { id: -1 }
            _mass = { 
                del_list: {},
                mod_list: {}
            };
        },
        
        _local_extract_time_and_id = function(p_masses) {
            var p_ret = {};
            for (var ind in p_masses)
            {
                var e = p_masses[ind];
                var _tmp_date = e.start_time,
                    _tmp_m = parseInt(_tmp_date % 100, 10),
                    _tmp_h = parseInt(_tmp_date / 100, 10);
                var _tmp_min = _tmp_m < 10 ? '0' + _tmp_m : _tmp_m;

                e['hours'] = _tmp_h;
                e['minutes'] = _tmp_min;

                p_ret[e.id] = e;
            }
            return p_ret;
        },
        
        _local_make_array = function(_obj) {
            var ret = $.map(_obj, function(k,v) {
                return [k];
            });
            var lcompare = function(a,b) {
                var vala = parseInt(a.hours, 10) * 100 + parseInt(a.minutes, 10),
                    valb = parseInt(b.hours, 10) * 100 + parseInt(b.minutes, 10);
                if (vala < valb) { return -1; }
                else if (vala > valb) { return 1; }
                else return 0;
             }
        
            ret.sort(lcompare);
            
            return ret;
        },
        
        _local_show_hide_save = function() {
            var dlist_empty = true, mlist_empty = true;
            for (var tmp in _mass.del_list) {
                dlist_empty = false;
                break;
            }
            for (tmp in _mass.mod_list) {
                mlist_empty = false;
                break;
            }
            
            if (
                    (_city.id < 0 && _city.new_name != undefined) || //new city
                    (_city.id >= 0 && _city.cur_name != _city.new_name) || //name change
                
                
                    //addd district change
                    (_district.id < 0 && _district.new_name != undefined) || //new district
                    (_district.id >= 0 && _district.cur_name != _district.new_name) || //name change
                    (_district.new_id != undefined) || //dist change
                        
                    (_church.id < 0 && _church.new_name != undefined) || // new church
                    (_church.id >= 0 && _church.cur_name != _church.new_name) || //name change
                        
                    (_church.id >= 0 && _church.cur_address != _church.new_address) || //address change
                    (_church.id >= 0 && _church.cur_desc != _church.new_desc) || //desc change
                    
                    (_church.id >= 0 && !dlist_empty) || //something to delete
                    (_church.id >= 0 && !mlist_empty) || //something to mod or add
                    
                    (_church.id >= 0 && 
                        ((_church.new_pos != undefined && _church.cur_pos == undefined) ||
                         (_church.new_pos != undefined && (_church.new_pos.lat != _church.cur_pos.lat || _church.new_pos.lng != _church.cur_pos.lng)))) ||
                    false
                )
            {
                fun_show_save();
            }
            else
            {
                fun_hide_save();
            }
        }
        ;
    
    self.addressTimer = undefined;
    
    self.setCityName = function(newName) {
        _city.new_name = newName;
        _local_show_hide_save();
        fun_city_select(_city.new_name + ' <sup class="small city_name_back">(cofnij zmianę)</sup>');
    }
    
    self.resetCityName = function() {
        _city.new_name = _city.cur_name;
        _local_show_hide_save();
        fun_city_select(_city.new_name);
    }
    
    self.setDistrictName = function(newName) {
        _district.new_name = newName;
        delete _district.new_id;
        _local_show_hide_save();
        fun_district_select(_district.new_name + ' <sup class="small district_name_back">(cofnij zmianę)</sup>');
    }
    
    self.setDistrictNewId = function(newId) {
        var found = undefined;
        for (var ob in _city.districts)
        {
            if (_city.districts[ob].id == newId) {
                found = _city.districts[ob].name;
                break;
            }
        }
        if (found != undefined) {
            if (found == '') {
                fun_district_select('Brak dzielnicy <sup class="small district_name_back">(cofnij zmianę)</sup>');
                fun_district_hide_edit();
            } else {
                fun_district_select(found + ' <sup class="small district_name_back">(cofnij zmianę)</sup>');
            }
            
            _district.new_id = newId;
            _local_show_hide_save();
        }
       
        
    }
    
    self.resetDistrictName = function() {
        _district.new_name = _district.cur_name;
        delete _district["new_id"];
        _local_show_hide_save();
        fun_district_select(_district.new_name);
    }
    
    self.setChurchName = function(newName) {
        _church.new_name = newName
        _local_show_hide_save();
        fun_church_select(_church.new_name + ' <sup class="small church_name_back">(cofnij zmianę)</sup>');
    }
    
    self.resetChurchName = function() {
        _church.new_name = _church.cur_name;
        _local_show_hide_save();
        fun_church_select(_church.new_name);
    }
    
    self.setChurchAddress = function(address) {
        _church["new_address"] = address;
        _local_show_hide_save();
    }
    self.setChurchDescription = function(desc) {
        _church["new_desc"] = desc;
        _local_show_hide_save();
    }
    self.setupPosition = function(lat, lng) {
        _church["new_pos"] = {};
        _church.new_pos["lat"] = lat;
        _church.new_pos["lng"] = lng;
        _local_show_hide_save();
    }
    self.resetPosition = function() {
        delete _church["new_pos"];
        _local_show_hide_save();
    }
    self.getGoogleGPSPos = function() {
        if (_church.new_pos == undefined)
        {
            return undefined;
        }
        return { lat: _church.new_pos.lat, lng: _church.new_pos.lng };
    }
    
    self.getNewMassId = function()
    {
        _new_mass_id_cnt = _new_mass_id_cnt - 1;
        return _new_mass_id_cnt;
    }
    
    self.showHideSave = function() 
    {
        _local_show_hide_save();
    }
    
    self.getCityName = function() { return _city.new_name; }
    self.getDistrictName = function() { return _district.new_name; }
    self.getChurchName = function() { return _church.new_name; }
    
    self.getCityId = function() { return _city.id; }
    self.getChurchId = function() { return _church.id; }
    self.getDistrictId = function() { return _district.id; }
    self.getGoogleSearch = function() 
    {      
        var _ret = '';
        if (_city.new_name != undefined && _city.new_name != '') {
            _ret += _city.new_name;
        }
        if (_church.new_address != undefined && _church.new_address != '') {
            _ret += ', ' + _church.new_address;
        }
        return _ret == '' ? undefined : _ret;
    }
    
    self.renderMasses = function() {
        var n_smasses = $.extend({}, _mass.smasses),
            n_wmasses = $.extend({}, _mass.wmasses);
        
        for (var ind in n_smasses) {
            n_smasses[ind]['del'] = false;
            n_smasses[ind]['mod'] = false;
           if (_mass.del_list[ind] != undefined) {
               n_smasses[ind]['del'] = true;
           } else if (_mass.mod_list[ind] != undefined) {
               n_smasses[ind] = _mass.mod_list[ind];
               n_smasses[ind]['mod'] = true;
           }
        }
        
        for (ind in n_wmasses) {
            n_wmasses[ind]['del'] = false;
            n_wmasses[ind]['mod'] = false;
            if (_mass.del_list[ind] != undefined) {
               n_wmasses[ind]['del'] = true;
           } else if (_mass.mod_list[ind] != undefined) {
               n_wmasses[ind] = _mass.mod_list[ind];
               n_wmasses[ind]['mod'] = true;
           }
        }
        
        for (ind in _mass.mod_list)
        {
           if (ind < 0 && _mass.mod_list[ind]['day_sun'] == true) {
               n_smasses[ind] = _mass.mod_list[ind];
           } else if (ind < 0 && _mass.mod_list[ind]['day_sun'] == false) {
               n_wmasses[ind] = _mass.mod_list[ind];
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
        _city.id = id;
            
        if (id == -1 && isNew == true)
        {
            _city.new_name = newName;
            _city.cur_name = newName;
            
            fun_city_hide_edit();
            fun_district_hide_edit();
            fun_church_hide_edit();
            
            fun_city_select(newName);
            fun_district_unselect();
            fun_district_show();
            fun_district_clear();
            
            fun_district_default_set(-100);
            
            fun_church_unselect();
            fun_church_hide();
            fun_church_clear();
            
            fun_mass_hide();
            fun_mass_clear();
            if (afterFun != undefined) { afterFun(); }
            
            _local_show_hide_save();
        }
        else if (id == -1 && isNew !== true)
        {            
            fun_city_hide_edit();
            fun_district_hide_edit();
            fun_church_hide_edit();
            fun_mass_clear();
            fun_mass_hide();
            fun_church_unselect();
            fun_church_hide();
            fun_district_unselect();
            fun_district_hide();
            fun_city_unselect();
            
            fun_district_default_set(-100);        
            if (afterFun != undefined) { afterFun(); }
            
            _local_show_hide_save();
        }
        else if (id != -1)
        {
            $('.main_add_division_table').oipLoading('show');
            $.oip.ajax.getJSON(('fast_city'), { id: id }, null, function(data) {
                _city.cur_name = data.name;
                _city.new_name = data.name;
                
                _city.districts = data.districts;
                _city.district_default = data.districts[0].id;
                
                fun_city_select(data.name);
                fun_district_fill(data.districts)
                fun_district_show();
                
                if (afterFun != undefined) { afterFun(); }
                fun_city_show_edit();
                
                $('.main_add_division_table').oipLoading('hide');
                _local_show_hide_save();
            });
        }
    }
    
    self.setupDistrictId = function(id, isNew, newName, afterFun) {
        _local_clear_district();
        _local_clear_church();
        _district.id = id;
        
        if (id == -100) 
        {
            fun_district_hide_edit();
            fun_church_hide_edit();
            fun_district_select('');
            fun_church_unselect();
            fun_church_show();
            fun_church_clear();
            fun_mass_hide();
            fun_mass_clear();
            if (afterFun != undefined) { afterFun(); }
            
            _local_show_hide_save();
        }
        else if (id == -1 && isNew == true)
        {
            _district.new_name = newName;
            _district.cur_name = newName;
            
            fun_district_hide_edit();
            fun_church_hide_edit();
            fun_district_select(newName);
            fun_church_unselect();
            fun_church_show();
            fun_church_clear();
            fun_mass_hide();
            fun_mass_clear();
            if (afterFun != undefined) { afterFun(); }
            
            _local_show_hide_save();
        }
        else if (id == -1 && isNew !== true)
        {
            fun_district_hide_edit();
            fun_church_hide_edit();            
            fun_mass_hide();
            fun_mass_clear();
            fun_church_unselect();
            fun_church_hide();
            fun_district_unselect();
            if (afterFun != undefined) { afterFun(); }
            
            _local_show_hide_save();
        }
        else if (id != -1)
        {
            $('.main_add_division_table').oipLoading('show');
            $.oip.ajax.getJSON(('fast_district'), { id: id }, null, function(data) {
                _district.cur_name = data.name;
                _district.new_name = data.name;
                
                fun_district_select(data.name);
                fun_church_fill(data.churches)
                fun_church_show();
                
                if (afterFun != undefined) { afterFun(); }
                if (data.name != '')
                {
                    fun_district_show_edit();
                }
                
                $('.main_add_division_table').oipLoading('hide');
                _local_show_hide_save();
            });
        }
    }
    
    self.setupChurchId = function(id, isNew, newName, afterFun) {        
        _local_clear_church();
        _church.id = id;
        
        if (id == -1 && isNew == true)
        {
            _church.cur_name = newName;
            _church.new_name = newName;
            
            _address_setup_allow = true;
            fun_church_hide_edit();
            fun_church_select(newName);
            fun_mass_clear();
            fun_mass_show();
            if (afterFun != undefined) { afterFun(); }
            
            fun_mass_fill([],[]);
            fun_mass_show();
            
            _local_show_hide_save();
        }
        else if (id == -1 && isNew !== true)
        {
            _address_setup_allow = false;
            fun_church_hide_edit();
            fun_church_unselect();
            fun_mass_hide();
            fun_mass_clear();
            if (afterFun != undefined) { afterFun(); }
            
            _local_show_hide_save();
        }
        else if (id != -1)
        {
            $('.main_add_division_table').oipLoading('show');
            $.oip.ajax.getJSON(('fast_church'), { id: id }, null, function(data) {
                
                _church.cur_name = data.name;
                _church.new_name = data.name;
            
                _church.cur_address = data.address;
                _church.new_address = data.address;
                
                _church.cur_desc = data.description;
                _church.new_desc = data.description;
                
                if (data.latitude != undefined && data.longitude != undefined)
                {
                    _church.cur_pos = {};
                    _church.cur_pos.lat = data.latitude;
                    _church.cur_pos.lng = data.longitude;
                    self.setupPosition(data.latitude, data.longitude);
                }
                
                _address_setup_allow = true;
                fun_church_select(data.name, data);
                _mass.smasses = _local_extract_time_and_id(data.smasses);
                _mass.wmasses = _local_extract_time_and_id(data.wmasses);
                fun_mass_fill(_local_make_array(_mass.smasses), _local_make_array(_mass.wmasses))
                fun_mass_show();
                if (afterFun != undefined) { afterFun(); }
                fun_church_show_edit();
                
                $('.main_add_division_table').oipLoading('hide');
                _local_show_hide_save();
            });
        }
    } 
    
    self.isMarkedForDeletion = function(id) {
        return _mass.del_list[id] != undefined;
    }
    
    self.markMassForDeletion = function(id) {
        if (id < 0)
        {
            delete _mass.mod_list[id];
        }
        else
        {
           _mass.del_list[id] = true;
        }
        self.renderMasses();
        _local_show_hide_save();
    }
    
    self.unmarkMassForDeletion = function(id) {
        delete _mass.del_list[id];
        self.renderMasses();
        _local_show_hide_save();
    }
    
    self.markMassForChange = function(id, values) {
        _mass.mod_list[id] = values;
        self.renderMasses();
        _local_show_hide_save();
    }
    
    self.unmarkMassForChange = function(id) {
        delete _mass.mod_list[id];
        self.renderMasses();
        _local_show_hide_save();
    }
    
    self.isAddressSetupAllowed = function() {
        return _address_setup_allow;
    }
    
    self.Reset = function() {
        if (_city.id < 0) {
            self.setupChurchId(-1);
            self.setupDistrictId(-1);
            self.setupCityId(-1);
        } else {
            _city.new_name = _city.cur_name;
            if (_district.id < 0) {
                self.setupDistrictId(-1);
                self.setupChurchId(-1);
            } else {
                _district.new_name = _district.cur_name;
                delete _district["new_id"];
                if (_church.id < 0) {
                    self.setupChurchId(-1);
                } else {
                    _church.new_name = _church.cur_name;

                    _church.new_address = _church.cur_address;
                    _church.new_desc = _church.cur_desc;

                    _church.new_pos = _church.cur_pos;
                    _mass.mod_list = {};
                    _mass.del_list = {};

                    fun_city_select(_city.new_name);
                    fun_district_select(_district.new_name);

                    var data = { description: _church.new_desc, address: _church.new_address};
                    if (_church.new_pos != undefined) {
                        data.latitude = _church.new_pos.lat;
                        data.longitude = _church.new_pos.lng;
                    }       

                    fun_church_select(_church.new_name, data);
                    
                    
                    self.renderMasses();
                }
            }
        }
        _local_show_hide_save();
        
    }
    
    self.Save = function(re_c, re_r) {
        var str = { 'cid': _city.id, 'cname': _city.new_name, 
                'did': (_district.new_id == undefined ? _district.id : _district.new_id), 
                'dname': (_district.new_id == undefined ? _district.new_name : undefined),
                'chid': _church.id, 'chname': _church.new_name,
                'caddr': _church.new_address,
                'cdesc': _church.new_desc,
                'clat': (_church.new_pos == undefined ? undefined : _church.new_pos.lat),
                'clng': (_church.new_pos == undefined ? undefined : _church.new_pos.lng),
                'mmod': _mass.mod_list,
                'mdel': _mass.del_list
              };
        $.oip.ajax.postJSON('save_all', null, {'all_data': str, 're_c': re_c, 're_r': re_r}, function(data) {
            if (data['error'] != undefined) {
                alert('Error' + data.error);
                Recaptcha.reload();
            } else {
                self.Reset();
                self.SetupAll(data.city_id, data.district_id, data.church_id);
                //location.href = Routing.generate('edit_or_add', {city_id: data.city_id, district_id: data.district_id, church_id: data.church_id});
            }
        });
    }
    
    self.SetupAll = function(city_id, district_id, church_id) {
        //construct
        if (city_id != -1) {
            $('.main_add_division_table').oipLoading('show');
            self.setupCityId(city_id, undefined, undefined, function() {
                if (district_id != -1) {
                    self.setupDistrictId(district_id, undefined, undefined, function() {
                        if (church_id != -1) {
                            self.setupChurchId(church_id, null, null, function() {
                                $('.main_add_division_table').oipLoading('hide');
                            });
                        } else {
                            $('.main_add_division_table').oipLoading('hide');
                        }
                    });
                } else {
                    $('.main_add_division_table').oipLoading('hide');
                }
            });
        }
    }
    
    self.SetupAll(city_id, district_id, church_id);
}

