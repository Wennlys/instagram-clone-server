import React, { useState } from 'react';
import AuthContext, { User } from './contexts/AuthContext';
import AuthenticatedRoutes from './routes/AuthenticatedRoutes';
import NotAutheticatedRoutes from './routes/NotAuthenticatedRoutes';
import api from './services/api';

const App: React.FC = () => {
  const [isSigned, setIsSigned] = useState<boolean>(false);
  async function handleLogin(userData: User) {
    const response = await api.post('/sessions', userData, {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
    });

    const token = response.data.data.token;
    const user = userData?.username;
    if (response.status === 200 && token && user) {
      localStorage.setItem('@App:token', token);
      localStorage.setItem('@App:user', user);
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      api.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
      setIsSigned(true);
    }
  }

  return (
    <AuthContext.Provider value={{ handleLogin }}>
      {isSigned ? <AuthenticatedRoutes /> : <NotAutheticatedRoutes />}
    </AuthContext.Provider>
  );
};

export default App;
