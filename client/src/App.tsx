import React, { useState } from 'react';
import AuthContext, { IUser } from './contexts/AuthContext';
import AuthenticatedRoutes from './routes/AuthenticatedRoutes';
import NotAutheticatedRoutes from './routes/NotAuthenticatedRoutes';
import api from './services/api';

const App: React.FC = () => {
  const [isSigned, setIsSigned] = useState<boolean>(false);
  async function Login(userData: IUser) {
    await api.post('/sessions', userData).then(res => setIsSigned(res.data[0].statusCode === 200 ? true : false));
  }

  return (
    <AuthContext.Provider value={{ isSigned, Login }}>
      {isSigned ? <AuthenticatedRoutes /> : <NotAutheticatedRoutes />}
    </AuthContext.Provider>
  );
};

export default App;
