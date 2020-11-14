import { createContext } from 'react';

// import { Container } from './styles';

export interface IUser {
  user_id: number;
  password: string;
}

export interface IAuthContext {
  isSigned: boolean;
  Login(user: IUser): Promise<void>;
}

const AuthContext = createContext<IAuthContext>({} as IAuthContext);

export default AuthContext;
