import React from 'react';
import { Route, Switch } from 'react-router-dom';
import Explore from '../pages/Explore';
import Home from '../pages/Home';

// import { Container } from './styles';

const AuthenticatedRoutes: React.FC = () => {
  return (
    <Switch>
      <Route exact path="/" component={Home} />
      <Route path="/explore" component={Explore} />
      <Route component={() => <>Not Found</>} />
    </Switch>
  );
};

export default AuthenticatedRoutes;
