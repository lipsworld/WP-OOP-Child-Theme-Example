import React from 'react';
import { Router, Route, Switch } from 'dva/router';
import IndexPage from './routes/IndexPage';
import Location from './routes/Location';
import Search from './routes/Search';

function RouterConfig({ history }) {
  return (
    <Router history={history}>
      <Switch>
        <Route path="/" exact component={IndexPage} />
        <Route path="/weather/:id" exact component={Location} />
        <Route path="/search/:query" exact component={Search} />
      </Switch>
    </Router>
  );
}

export default RouterConfig;
