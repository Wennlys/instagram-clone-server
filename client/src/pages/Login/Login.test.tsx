import { screen } from '@testing-library/dom';
import React from 'react';
import Login from '.';
import AuthContext from '../../contexts/AuthContext';
import { renderWithRouter } from '../../utils/test.utils';

describe('Login rendering', () => {
  test('login component renders correctly', () => {
    const handleLogin = jest.fn();
    renderWithRouter(
      <AuthContext.Provider value={{ isSigned: true, handleLogin }}>
        <Login />
      </AuthContext.Provider>,
    );
    screen.getByText(/Log In/).click();
  });
});
