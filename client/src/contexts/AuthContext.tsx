import { createContext } from 'react';

// import { Container } from './styles';

export interface User {
  username: string;
  password: string;
}

export interface IAuthContext {
  handleLogin(user: User): Promise<void>;
}

const AuthContext = createContext<IAuthContext>({} as IAuthContext);

export default AuthContext;
