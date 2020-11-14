import React, { useContext } from 'react';
import AuthContext from '../../contexts/AuthContext';

const Login: React.FC = () => {
  const { handleLogin } = useContext(AuthContext);
  function handleSubmit(): void {
    handleLogin({ user_id: 1, password: '' });
  }
  return (
    <button onClick={() => handleSubmit()} type="submit">
      Log In
    </button>
  );
};

export default Login;
