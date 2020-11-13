import React, { useState } from 'react';
import AuthenticatedRoutes from './routes/AuthenticatedRoutes';
import NotAutheticatedRoutes from './routes/NotAuthenticatedRoutes';
import api from './services/api';

const App: React.FC = () => {
  const [isSigned, setIsSigned] = useState(false);
  api
    .post('/sessions', {
      user_id: 1,
      password: '123456',
    })
    .then(res => setIsSigned(res.data[0].statusCode == 200 ? true : false));

  return <>{isSigned ? <AuthenticatedRoutes /> : <NotAutheticatedRoutes />}</>;
};

export default App;
