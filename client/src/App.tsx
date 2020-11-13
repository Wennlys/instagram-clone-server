import React, { useState } from 'react';
import { Route } from 'react-router-dom';
import Home from './pages/Home';
import Login from './pages/Login';
import api from './services/api';

const App: React.FC = () => {
  const [isSigned, setIsSigned] = useState(false);
  api
    .post('/sessions', {
      user_id: 1,
      password: '123456',
    })
    .then(res => setIsSigned(res.data[0].statusCode == 200 ? true : false));

  return (
    <>
      {isSigned ? <Route path="/" component={Home} /> : <Route path="/" component={Login} />}
      <Route component={() => <div>Not Found</div>} />
    </>
  );
};

export default App;
