import React, { useState } from 'react';
import AuthContext, { IUser } from './contexts/AuthContext';
import AuthenticatedRoutes from './routes/AuthenticatedRoutes';
import NotAutheticatedRoutes from './routes/NotAuthenticatedRoutes';
import api from './services/api';

const App: React.FC = () => {
  const [isSigned, setIsSigned] = useState<boolean>(false);
  async function handleLogin(userData: IUser) {
    const response = await api.post('/sessions', userData);
    setIsSigned(response.status == 200 ? true : false);
  }

  return (
    <AuthContext.Provider value={{ isSigned, handleLogin }}>
      {isSigned ? <AuthenticatedRoutes /> : <NotAutheticatedRoutes />}
    </AuthContext.Provider>
  );
};

export default App;
