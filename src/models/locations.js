import { updateOrNew } from '../utils';

const base = 'http://wp.local/weather.php';

export default {

  namespace: 'locations',

  state: {
    locations: [],
    searchResult: []
  },

  effects: {
    *fetch({ payload }, { call, put, select }) {  // eslint-disable-line
      // payload is array of cities

      var url = base + '?command=search&keyword=';
      var cities = [...payload]; // make a copy of city array

      const locations = yield select(state => state.locations.locations);

      let exists = [];
      cities = cities.filter((city, i) => {
        // if data for the given city already exists
        const loc = locations.filter(location => location.title === city);
        
        if(loc.length > 0){
          exists.push(loc.pop());
          return false;
        }
        return true;
      });
      
      if(cities.length > 0){
        try {
          const data = yield Promise.all(
            cities.map(city => fetch(url + encodeURIComponent(city)))
          ).then(responses => Promise.all(responses.map(res => res.json()))
          ).then(data => {
            return data.flat();
          });
          
          yield put({type: 'save', payload: exists.concat(data)});
        }
        catch(err) {
          console.log(err);
        };
      }

    },
    *search({payload}, {call, put}){
      // http://wp.local/weather.php?command=search&keyword=dhaka
      payload = encodeURIComponent(payload);
      const url = base + '?command=search&keyword=' + payload;
      let data = yield fetch(url).then(data => data.json()).then(data => data);
      yield put({type: 'saveSearchResult', payload: data});
      yield put({type: 'saveSearchQuery', payload: payload})
      
    },
    *single({payload}, {select, put, call}){
      const woeid = parseInt(payload.woeid, 10);
      
      let data = yield select((state => {
        const source = payload.isSearch ? state.locations.searchResult : state.locations.locations;
        return source.filter(location => location.woeid === woeid);
      }));
      
      if(data.length === 0){
        // call api
        const url = base + '?command=location&woeid=' + payload.woeid;
        data = yield fetch(url).then(data => data.json()).then(data => data);
        yield put({type: 'saveSingle', payload: data});
      }else{
        data = data.pop();
      }

      yield put({type: 'saveCurrent', payload: data});

    },
    *fetchSingle({payload}, {put}){
      // http://wp.local/weather.php?command=location&woeid=1915035
      const url = base + '?command=location&woeid=' + payload.woeid;
      const data = yield fetch(url).then(data => data.json()).then(data => data);
      
      if(payload.search){
        yield put({type: 'saveSearchResultSingle', payload: data})
      }else{
        yield put({type: 'saveSingle', payload: data})
      }
      
    }
  },

  reducers: {
    save(state, action) {
      return { ...state, locations: [...action.payload] };
    },
    saveSingle(state, {payload}){

      let locations = [...state.locations];
      const find = locations.filter(location => location.woeid === payload.woeid).length > 0;
      
      if(find){
        locations = locations.map(location => {
          return location.woeid === payload.woeid ? {...payload} : location;
        })        
      }else{
        locations.push(payload);
      }
      
      return { ...state, locations: locations };
    },
    saveCurrent(state, {payload}){
      return {...state, current: payload}
    },
    saveSearchResult(state, {payload}){
      return {...state, searchResult: [...payload]}
    },
    saveSearchResultSingle(state, {payload}){
      const locations = updateOrNew(state.searchResult, payload, 'woeid');
      return { ...state, searchResult: locations };
    },
    saveSearchQuery(state, {payload}){
      return { ...state, prevSearchQuery: payload};
    }
  },

};
