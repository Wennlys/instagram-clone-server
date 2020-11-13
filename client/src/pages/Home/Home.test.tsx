import { screen } from '@testing-library/dom';
import { render } from '@testing-library/react';
import React from 'react';
import Home from '.';
describe('Login rendering', () => {
  test('login component renders correctly', () => {
    render(<Home />);
    expect(screen.getByText(/Home/i)).toBeInTheDocument();
  });
});
