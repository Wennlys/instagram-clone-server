import React, { useContext } from 'react';
import AuthContext from '../../contexts/AuthContext';

const Login: React.FC = () => {
  const { handleLogin } = useContext(AuthContext);

  return (
    <button onClick={() => handleLogin({ username: 'new_user', password: '123456' })} type="submit">
      Log In
    </button>
  );
};

export default Login;
