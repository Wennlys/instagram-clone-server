import React, { useEffect, useState } from 'react';
import Homepage from './pages/Homepage';
import Login from './pages/Login';
import api from './services/api';

function App(): JSX.Element {
  const [isLogged, setIsLogged] = useState(false);
  useEffect(() => {
    async function handleIsLogged() {
      const { data } = await api.post('/sessions', {
        user_id: 1,
        password: 12345678,
      });
      console.log(data);
      if (data[0].statusCode == 200) {
        return true;
      }
      return false;
    }

    handleIsLogged().then(res => setIsLogged(res));
  }, [isLogged]);

  return <div className="App">{isLogged ? <Homepage /> : <Login />}</div>;
}

export default App;
