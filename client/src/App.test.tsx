import '@testing-library/jest-dom/extend-expect';
import { cleanup, screen } from '@testing-library/react';
import React from 'react';
import { act } from 'react-dom/test-utils';
import App from './App';
import api from './services/api';
import { renderWithRouter } from './utils/test.utils';

jest.mock('./services/api');
const mockedApi = api as jest.Mocked<typeof api>;

afterEach(() => {
  cleanup();
  jest.resetAllMocks();
});

describe('App rendering & routes', () => {
  it('renders Login component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 404,
          },
        ],
      }),
    );
    const { getByText } = renderWithRouter(<App />);
    expect(getByText(/Log In/i)).toBeInTheDocument();
  });

  test("authentication & Homepage's component rendering", async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 200,
          },
        ],
      }),
    );
    await act(async () => {
      await renderWithRouter(<App />);
      (await screen.findByText(/Log In/i)).click();
    });
    expect(screen.getByText(/Home/i)).toBeInTheDocument();
  });

  it('renders 404 component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 404,
          },
        ],
      }),
    );
    renderWithRouter(<App />, '/404');
    expect(screen.getByText(/Not Found/i)).toBeInTheDocument();
  });

  it('renders Explore component', async () => {
    mockedApi.post.mockImplementation(() =>
      Promise.resolve({
        data: [
          {
            statusCode: 200,
          },
        ],
      }),
    );
    await act(async () => {
      await renderWithRouter(<App />);
      (await screen.findByText(/Log In/i)).click();
      (await screen.findByText(/Explore/i)).click();
    });
    expect(screen.getByText(/Explore/i)).toBeInTheDocument();
  });
});
