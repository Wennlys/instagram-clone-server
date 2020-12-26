import { screen } from '@testing-library/react';
import React from 'react';
import Login from '.';
import AuthContext from '../../contexts/AuthContext';
import { renderWithRouter } from '../../utils/test.utils';

describe('Login rendering', () => {
  test('login component renders correctly', () => {
    const handleLogin = jest.fn();
    renderWithRouter(
      <AuthContext.Provider value={{ handleLogin }}>
        <Login />
      </AuthContext.Provider>,
    );
    screen.getByText(/Log In/).click();
    expect(handleLogin).toBeCalled();
  });
});
