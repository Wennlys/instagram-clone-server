import React from 'react';
import { Route, Switch } from 'react-router-dom';
import Login from '../pages/Login';

// import { Container } from './styles';

const NotAutheticatedRoutes: React.FC = () => {
  return (
    <Switch>
      <Route exact path="/" component={Login} />
      <Route component={() => <div>Not Found</div>} />
    </Switch>
  );
};

export default NotAutheticatedRoutes;
